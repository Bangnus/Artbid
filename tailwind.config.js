/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    'bg-red-600',
    'bg-green-600',
    'bg-gray-500',
  ],
  theme: {
    extend: {
      fontFamily: {
        primaryRegular: ['regular'],
        primaryBold: ['bold'],
        primarySemibold: ['semibold'],
        primaryMedium: ['medium'],
      },
      colors: {
        bgprimary: "#EA5734",
        bgsecondary: "#00365D",
        bgligth: "#F5F5F5",
        textprimary: '#333333',
        textsecondary: '#7D7D7D',
        textligth: "#F5F5F5",
        textorange: '#EA5734',
      },
    },
    screens: {
      xs: '412px',
      sm: '640px',
      md: '768px',
      lg: '1025px',
      xl: '1280px',
      '2xl': '1536px',
    },

  },
  plugins: [],
}

