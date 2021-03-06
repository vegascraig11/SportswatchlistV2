const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  purge: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: false,
  theme: {
    extend: {
      fontSize: {
        xxs: "0.6rem",
      },
      fontFamily: {
        sans: ["Lexend Deca", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        mantis: {
          100: "#F1F9EE",
          200: "#DDF0D5",
          300: "#C8E6BC",
          400: "#9ED489",
          500: "#75C157",
          600: "#69AE4E",
          700: "#467434",
          800: "#355727",
          900: "#233A1A",
        },
        "swl-black": {
          lighter: "#27282c",
          light: "#1c1e21",
          dark: "#0b0b0b",
        },
      },
    },
  },
  variants: {
    extend: {
      backgroundOpacity: ["disabled"],
      cursor: ["disabled"],
    },
  },
  plugins: [],
  experimental: {
    applyComplexClasses: true,
  },
};
