/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{php,html,js}",
    "./components/**/*.{php,html}",
    "./auth/**/*.{php,html}",
    "./includes/**/*.{php,html}",
    "./src/**/*.{php,html,js,svg}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#2563eb',       // Blue
        secondary: '#1e293b',     // Dark Slate
        accent: '#f59e0b',        // Amber
        neutral: '#64748b',       // Gray
        surface: '#f8fafc',       // Light Surface
        darkSurface: '#1f2937',   // Darker Surface
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        'btn': '0.5rem',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      boxShadow: {
        soft: '0 2px 12px rgba(0, 0, 0, 0.05)',
        chat: '0 0 20px rgba(37, 99, 235, 0.2)',
      },
      animation: {
        pulseFast: 'pulse 1.2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        fadeIn: 'fadeIn 0.6s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: 0, transform: 'translateY(10px)' },
          '100%': { opacity: 1, transform: 'translateY(0)' }
        }
      },
      fill: theme => theme('colors'),
      stroke: theme => theme('colors'),
      transitionTimingFunction: {
        'in-expo': 'cubic-bezier(0.95, 0.05, 0.795, 0.035)',
        'out-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ],
};
