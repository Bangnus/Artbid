/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}",
    "./**/*.php",
    "./public/**/*.{html,js,php}"
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
        textblue: '#1E9CF5'
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

