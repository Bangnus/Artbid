/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
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
      '2xl': { 'max': '1535px' },
      // => @media (max-width: 1535px) { ... }
      'xl': { 'max': '1279px' },
      // => @media (max-width: 1279px) { ... }
      'lg': { 'max': '1023px' },
      // => @media (max-width: 1023px) { ... }
      'md': { 'max': '767px' },
      // => @media (max-width: 767px) { ... }
      'sm': { 'max': '640px' },
      // => @media (max-width: 639px) { ... }
      'xs': { 'max': '412px'},
       // => @media (max-width: 410px) { ... }
    },
  },
  plugins: [],
}

