/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{php,html,js}",
    "./components/**/*.{php,html}",
    "./auth/**/*.{php,html}",
    "./includes/**/*.{php,html}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#2563eb',
        secondary: '#1e293b', // Dark Slate
        accent: '#f59e0b', // Warm yellow
        neutral: '#64748b', // Cool gray
        surface: '#f8fafc', // Very light
        darkSurface: '#1f2937', // Dark gray
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
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ],
}
