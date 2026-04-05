import './bootstrap';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// ── Sound Engine (Web Audio API — no external file needed) ─────────────────
const AudioCtx = window.AudioContext || window.webkitAudioContext;

function playTone(freq = 880, type = 'sine', duration = 0.3, volume = 0.4) {
    try {
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = type;
        osc.frequency.setValueAtTime(freq, ctx.currentTime);
        gain.gain.setValueAtTime(volume, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + duration);
    } catch (e) {}
}

function playNewOrderSound() {
    // Rising two-tone "new order" chime
    playTone(660, 'sine', 0.2, 0.5);
    setTimeout(() => playTone(880, 'sine', 0.3, 0.5), 200);
}

function playUrgentSound() {
    // Rapid triple beep for urgent orders
    [0, 180, 360].forEach(delay => {
        setTimeout(() => playTone(1000, 'square', 0.15, 0.6), delay);
    });
}

function playStatusSound() {
    // Gentle single chime for status updates
    playTone(528, 'sine', 0.4, 0.3);
}

// ── Toast Notification ────────────────────────────────────────────────────
function showToast(title, body, type = 'info', duration = 6000) {
    const id = 'toast-' + Date.now();
    const accents = {
        info:    'border-violet-500',
        success: 'border-green-500',
        urgent:  'border-red-500',
        warning: 'border-orange-500',
    };

    const container = document.getElementById('toast-container') || (() => {
        const c = document.createElement('div');
        c.id = 'toast-container';
        c.className = 'fixed bottom-4 right-4 z-[9999] flex flex-col-reverse gap-3 w-80 pointer-events-none';
        document.body.appendChild(c);
        return c;
    })();

    const toast = document.createElement('div');
    toast.id = id;
    toast.className = `
        bg-white border-l-4 ${accents[type] ?? accents.info} p-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)]
        flex items-start gap-3 translate-x-full opacity-0 transition-all duration-500 cursor-pointer pointer-events-auto
    `.trim();

    toast.innerHTML = `
        <div class="text-2xl shrink-0">${type === 'urgent' ? '⚡' : type === 'success' ? '✅' : '🔔'}</div>
        <div class="flex-1 min-w-0">
            <div class="font-bold text-slate-900 text-sm font-sans">${title}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1 truncate">${body}</div>
        </div>
        <button onclick="this.closest('.transition-all').remove()" class="text-slate-300 hover:text-slate-500 text-lg leading-none shrink-0 transition-colors">✕</button>
    `;

    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        });
    });

    // Auto remove
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, duration);
}

// ── Browser Notification (if permitted) ──────────────────────────────────
function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

function sendBrowserNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, { body, icon: '/favicon.ico' });
    }
}

// ── Reverb: Public Orders Channel (Admin + Manager) ──────────────────────
window.Echo.channel('orders')
    .listen('.OrderPlaced', (e) => {
        if (e.isUrgent) {
            playUrgentSound();
            showToast(
                `⚡ طلب عاجل جديد! / Urgent Order!`,
                `${e.orderNumber} — ${e.clientName} — ${e.total} SAR`,
                'urgent',
                10000
            );
            sendBrowserNotification('طلب عاجل جديد!', `${e.orderNumber} — ${e.clientName}`);
        } else {
            playNewOrderSound();
            showToast(
                `🆕 طلب جديد / New Order`,
                `${e.orderNumber} — ${e.clientName} — ${e.total} SAR`,
                'info',
                7000
            );
            sendBrowserNotification('طلب جديد وصل', `${e.orderNumber} — ${e.total} SAR`);
        }

        // Trigger Livewire component refresh if admin/manager dashboard loaded
        if (window.Livewire) {
            window.Livewire.dispatch('order-placed', { orderId: e.orderId });
        }
    })
    .listen('.OrderStatusChanged', (e) => {
        playStatusSound();
        showToast(
            `📋 Order ${e.orderNumber}`,
            `Status → ${e.statusLabel}`,
            'success'
        );
        if (window.Livewire) {
            window.Livewire.dispatch('order-status-changed', { orderId: e.orderId });
        }
    });

// ── Reverb: Private Technician Channel ───────────────────────────────────
const techId = document.querySelector('meta[name="technician-id"]')?.content;
if (techId) {
    window.Echo.private(`technician.${techId}`)
        .listen('.OrderPlaced', (e) => {
            playUrgentSound();
            showToast(
                `🛠 مهمة جديدة لك! / New Job Assigned`,
                `${e.orderNumber} — ${e.address} — ${e.total} SAR`,
                'warning',
                12000
            );
            sendBrowserNotification('مهمة جديدة!', `${e.orderNumber} — ${e.address}`);

            if (window.Livewire) {
                window.Livewire.dispatch('order-placed');
            }
        });
}

// ── Private Client Channel ────────────────────────────────────────────────
const clientId = document.querySelector('meta[name="client-id"]')?.content;
if (clientId) {
    window.Echo.private(`client.${clientId}`)
        .listen('.OrderStatusChanged', (e) => {
            playStatusSound();
            const isCompleted = e.status === 'completed';
            showToast(
                isCompleted ? '✅ تم اكتمال طلبك!' : `📍 تحديث الطلب`,
                `${e.orderNumber} — ${e.statusLabel}${e.techName ? ' by ' + e.techName : ''}`,
                isCompleted ? 'success' : 'info'
            );
            if (window.Livewire) {
                window.Livewire.dispatch('order-status-changed');
            }
        });
}

// ── First-Visit Onboarding Tour ──────────────────────────────────────────
window.KifahTour = {
    isAr: () => document.documentElement.lang?.startsWith('ar'),

    steps: {
        home: [
            {
                icon: '🏠',
                en: { title: 'Welcome to Al-Kifah!', body: 'Browse our professional service categories. Tap any card to explore available services.' },
                ar: { title: 'مرحباً بك في الكفاح!', body: 'تصفح فئات خدماتنا المهنية. اضغط على أي بطاقة لاستعراض الخدمات المتاحة.' }
            },
            {
                icon: '📋',
                en: { title: 'Choose Your Service', body: 'Select a sub-service, set the quantity and timing (scheduled or urgent), then confirm your order.' },
                ar: { title: 'اختر خدمتك', body: 'اختر الخدمة الفرعية، حدد الكمية والتوقيت (مجدول أو عاجل)، ثم أكد طلبك.' }
            },
            {
                icon: '⚡',
                en: { title: 'Fast Dispatch', body: 'A verified technician is dispatched to your location. Track them live from your dashboard.' },
                ar: { title: 'إسناد سريع', body: 'يُرسَل فني معتمد إلى موقعك. تابع الطلب مباشرةً من لوحة تحكمك.' }
            },
        ],
        admin: [
            {
                icon: '📊',
                en: { title: 'Admin Dashboard', body: 'View live revenue, orders and user stats. All data refreshes automatically every few seconds.' },
                ar: { title: 'لوحة تحكم المسؤول', body: 'اعرض الإيرادات والطلبات وإحصائيات المستخدمين. تتحدث جميع البيانات تلقائياً.' }
            },
            {
                icon: '🚀',
                en: { title: 'Live Orders', body: 'Assign technicians to pending orders, force-dispatch, or cancel from the Orders tab.' },
                ar: { title: 'الطلبات المباشرة', body: 'أسند فنيين للطلبات المعلقة أو أصدر أمر إسناد فوري أو إلغاء من تبويب الطلبات.' }
            },
            {
                icon: '👥',
                en: { title: 'Agent Management', body: 'Add technicians, verify their profiles, toggle availability. Unverified agents cannot accept jobs.' },
                ar: { title: 'إدارة الوكلاء', body: 'أضف فنيين، تحقق من ملفاتهم، وفعّل إتاحتهم. الوكلاء غير الموثوقين لا يستقبلون مهاماً.' }
            },
        ],
    },

    hasSeenTour(page) {
        return localStorage.getItem(`kifah_tour_${page}`) === '1';
    },

    markSeen(page) {
        localStorage.setItem(`kifah_tour_${page}`, '1');
    },

    launch(page) {
        if (this.hasSeenTour(page)) return;
        const steps = this.steps[page];
        if (!steps) return;
        const isAr = this.isAr();

        // Build overlay
        const overlay = document.createElement('div');
        overlay.className = 'tour-overlay';
        overlay.setAttribute('dir', isAr ? 'rtl' : 'ltr');
        // Dismiss on backdrop click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.markSeen(page);
                overlay.remove();
            }
        });

        let current = 0;

        const render = () => {
            const s = steps[current];
            const t = isAr ? s.ar : s.en;
            const dotsHtml = steps.map((_, i) =>
                `<span class="tour-dot ${i === current ? 'active' : ''}"></span>`
            ).join('');

            overlay.innerHTML = `
                <div class="tour-card">
                    <div style="display:flex;justify-content:center;margin-bottom:16px;">
                        <div style="font-size:48px;line-height:1;">${s.icon}</div>
                    </div>
                    <h2 style="color:#e2d9f3;font-size:20px;font-weight:700;text-align:center;margin-bottom:10px;font-family:'Outfit',sans-serif;">
                        ${t.title}
                    </h2>
                    <p style="color:rgba(255,255,255,0.6);font-size:14px;text-align:center;line-height:1.7;margin-bottom:24px;">
                        ${t.body}
                    </p>
                    <div style="display:flex;justify-content:center;gap:6px;margin-bottom:20px;">
                        ${dotsHtml}
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button id="tour-skip" style="
                            flex:1;padding:12px;background:rgba(255,255,255,0.08);
                            border:1px solid rgba(255,255,255,0.1);border-radius:14px;
                            color:rgba(255,255,255,0.5);font-size:13px;cursor:pointer;">
                            ${isAr ? 'تخطَّ' : 'Skip'}
                        </button>
                        <button id="tour-next" style="
                            flex:2;padding:12px;
                            background:linear-gradient(135deg,#f26829,#8b5cf6);
                            border:none;border-radius:14px;
                            color:#fff;font-size:14px;font-weight:700;cursor:pointer;">
                            ${current < steps.length - 1
                                ? (isAr ? 'التالي →' : '→ Next')
                                : (isAr ? '✓ ابدأ الآن' : '✓ Get Started')}
                        </button>
                    </div>
                </div>
            `;

            overlay.querySelector('#tour-skip')?.addEventListener('click', () => {
                this.markSeen(page);
                overlay.remove();
            });

            overlay.querySelector('#tour-next')?.addEventListener('click', () => {
                if (current < steps.length - 1) {
                    current++;
                    render();
                } else {
                    this.markSeen(page);
                    overlay.remove();
                }
            });
        };

        render();
        document.body.appendChild(overlay);
    },

    resetAll() {
        ['home', 'admin'].forEach(p => localStorage.removeItem(`kifah_tour_${p}`));
    }
};

// ── Init ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    requestNotificationPermission();

    // Unlock Web Audio on first interaction (browser requirement)
    document.addEventListener('click', () => {
        try { new AudioCtx(); } catch(e) {}
    }, { once: true });
});

// ── PWA Service Worker ────────────────────────────────────────────────────
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .catch(() => {});
    });
}
