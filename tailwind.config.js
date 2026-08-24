/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./admin/index.php",
    "./assets/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Poppins', 'sans-serif'],
      },
      colors: {
        theme: {
          50: '#f2f5f7',
          100: '#e5eaf0',
          200: '#cdd7e3',
          300: '#a3b7d1',
          400: '#7392bc',
          500: '#4e6fa0',
          600: '#3c5a87',
          700: '#324a6f',
          800: '#2c3e5a',
          900: '#192a3d',
          gold: '#9c654d', // Accessible color (originally #AE7D64)
          beige: '#ffffff',
        },
        emerald: {
          50: '#f2f5f7',
          100: '#e5eaf0',
          200: '#cdd7e3',
          300: '#a3b7d1',
          400: '#2872fa',
          500: '#2872fa',
          600: '#2872fa',
          700: '#1d4ed8',
          800: '#9c654d', // Accessible color (originally #AE7D64)
          900: '#192a3d',
          950: '#192a3d',
        },
        amber: {
          50: '#f7f4f2',
          100: '#f7f4f2',
          200: '#ebdcd3',
          300: '#d7b9a7',
          400: '#9c654d', // Accessible color (originally #AE7D64)
          500: '#9c654d', // Accessible color (originally #AE7D64)
          600: '#9c654d', // Accessible color (originally #AE7D64)
          700: '#91624a',
          800: '#734e3a',
          900: '#5a3d2e',
        }
      }
    },
  },
  plugins: [],
}
