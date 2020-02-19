const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: ["Arial", ...defaultTheme.fontFamily.sans],
        inter: ["Inter", ...defaultTheme.fontFamily.sans]
      },
      colors: {
        "swl-green": "#75c157",
        "swl-black": {
          lighter: "#27282c",
          light: "#1c1e21",
          dark: "#0b0b0b"
        }
      }
    }
  },
  variants: {},
  plugins: []
};
