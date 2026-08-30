import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',       // semua Blade (termasuk home)
        './resources/js/**/*.js',                 // JS yang mengandung class Tailwind
        './app/View/Components/**/*.php',         // komponen Blade
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#10B981',
                    50: '#ECFDF5', 100: '#D1FAE5', 200: '#A7F3D0', 300: '#6EE7B7',
                    400: '#34D399', 500: '#10B981', 600: '#059669',
                    700: '#047857', 800: '#065F46', 900: '#064E3B'
                }
            },
            boxShadow: {
                glow: '0 0 0 6px rgba(16,185,129,0.15), 0 10px 50px rgba(16,185,129,0.25)'
            },
            keyframes: {
                floaty: {
                    '0%,100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                shimmer: { '100%': { transform: 'translateX(100%)' } },
                blob: {
                    '0%': { transform: 'translate(0,0) scale(1)' },
                    '33%': { transform: 'translate(20px,-30px) scale(1.05)' },
                    '66%': { transform: 'translate(-20px,10px) scale(0.975)' },
                    '100%': { transform: 'translate(0,0) scale(1)' }
                },
                pulseDot: {
                    '0%': { boxShadow: '0 0 0 0 rgba(16,185,129,0.6)' },
                    '70%': { boxShadow: '0 0 0 10px rgba(16,185,129,0)' },
                    '100%': { boxShadow: '0 0 0 0 rgba(16,185,129,0)' }
                },
            },
            animation: {
                floaty: 'floaty 6s ease-in-out infinite',
                shimmer: 'shimmer 1.2s linear infinite',
                blob: 'blob 14s ease-in-out infinite',
                'pulse-dot': 'pulseDot 1.8s ease-out infinite'
            },
        },
    },

    safelist: [
        // biar kelas custom di halaman home gak terhapus waktu build
        'text-brand', 'bg-brand/20', 'border-brand/30',
        'animate-[shimmer_1.8s_linear_infinite]',
        'animate-floaty', 'pulse-dot', 'snap-none', 'glass',
        'bg-grid', 'no-scrollbar', 'reveal', 'show'
    ],

    plugins: [forms],
};
