const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  theme: {
    extend: {
      fontSize: {
        xxs: "0.6rem",
      },
      fontFamily: {
        sans: ["Arial", ...defaultTheme.fontFamily.sans],
        inter: ["Inter", ...defaultTheme.fontFamily.sans],
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
    visibility: ["group-hover"],
  },
  plugins: [],
  purge: [
    "./resources/js/pages/**/*.vue",
    "./resources/js/components/**/*.vue",
    "./resources/js/containers/**/*.vue",
  ],
};
