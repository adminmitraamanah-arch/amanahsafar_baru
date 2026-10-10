/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.php',
    './public/**/*.html',
    './public/**/*.js'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
      },
      colors: {
        brand: {
          DEFAULT: '#5e0078',
          50: '#fbf5fd',
          100: '#f6eafb',
          200: '#edd5f7',
          300: '#dfb2f1',
          400: '#ca82e6',
          500: '#8f20ad',
          600: '#5e0078',
          700: '#4c0062',
          800: '#3b004c',
          900: '#280034',
          950: '#17001e',
        },
        blue: {
          50: '#fbf5fd',
          100: '#f6eafb',
          200: '#edd5f7',
          300: '#dfb2f1',
          400: '#ca82e6',
          500: '#8f20ad',
          600: '#5e0078',
          700: '#4c0062',
          800: '#3b004c',
          900: '#280034',
          950: '#17001e',
        },
        indigo: {
          50: '#faf3fc',
          100: '#f5e4fa',
          200: '#ebcaf5',
          300: '#dda5ee',
          400: '#c876e2',
          500: '#8f20ad',
          600: '#73139b',
          700: '#5e0078',
          800: '#450059',
          900: '#320040',
          950: '#1f0028',
        },
        slate: {
          700: '#3f0452',
          800: '#290035',
          900: '#1b0024',
        }
      }
    },
  },
  plugins: [],
}
