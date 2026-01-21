const plugin = require("tailwindcss/plugin");
const colors = require("tailwindcss/colors");

module.exports = {
  purge: {
    enabled: true,
    content: [
      "./public/**/*.html",
      "./public/*.html",
      "./src/**/*.js",
      "./src/*.js",
      "./src/**/*.html",
      "./src/*.html",
      "./public/**/*.js",
      "./public/*.js",
      // Ajout des fichiers Laravel Blade
      "./resources/**/*.blade.php",
      "./resources/**/*.php",
      "./resources/**/*.js",
      "./storage/framework/views/*.php",
    ],
    options: {
      safelist: [
        // Couleurs IRI pour éviter la purge
        'bg-iri-primary',
        'bg-iri-secondary',
        'bg-iri-accent',
        'bg-iri-light',
        'bg-iri-gold',
        'text-iri-primary',
        'text-iri-secondary',
        'text-iri-accent',
        'text-iri-light',
        'text-iri-gold',
        'border-iri-primary',
        'border-iri-secondary',
        'border-iri-accent',
        'hover:bg-iri-primary',
        'hover:bg-iri-secondary',
        'hover:text-iri-primary',
        'hover:text-iri-secondary',
        'focus:ring-iri-primary',
        'focus:border-iri-primary',
        'from-iri-primary',
        'to-iri-secondary',
        'hover:from-iri-secondary',
        'hover:to-iri-primary',
      ],
    },
  },
  theme: {
    colors: {
      ...colors,
      // Couleurs personnalisées IRI-UCBC
      'olive': '#505c10',
      'light-green': '#dde3da',
      'light-gray': '#f6f6f1',
      'coral': '#ee6751',
      'beige': '#f1ebe3',
      'grayish': '#eeeeee',
      // Couleurs IRI Charte Graphique
      'iri-primary': '#1e472f',
      'iri-secondary': '#2d5a3f',
      'iri-accent': '#d2691e',
      'iri-light': '#f0f9f4',
      'iri-gold': '#b8860b',
      'iri-gray': '#64748b',
      'iri-dark': '#1a1a1a',
    },
    extend: {
      minHeight: {
        "screen-75": "75vh",
      },
      fontSize: {
        55: "55rem",
      },
      opacity: {
        80: ".8",
      },
      zIndex: {
        2: 2,
        3: 3,
      },
      inset: {
        "-100": "-100%",
        "-225-px": "-225px",
        "-160-px": "-160px",
        "-150-px": "-150px",
        "-94-px": "-94px",
        "-50-px": "-50px",
        "-29-px": "-29px",
        "-20-px": "-20px",
        "25-px": "25px",
        "40-px": "40px",
        "95-px": "95px",
        "145-px": "145px",
        "195-px": "195px",
        "210-px": "210px",
        "260-px": "260px",
      },
      height: {
        "95-px": "95px",
        "70-px": "70px",
        "350-px": "350px",
        "500-px": "500px",
        "600-px": "600px",
      },
      maxHeight: {
        "860-px": "860px",
      },
      maxWidth: {
        "100-px": "100px",
        "120-px": "120px",
        "150-px": "150px",
        "180-px": "180px",
        "200-px": "200px",
        "210-px": "210px",
        "580-px": "580px",
      },
      minWidth: {
        "140-px": "140px",
        48: "12rem",
      },
      backgroundSize: {
        full: "100%",
      },
    },
  },
  variants: [
    "responsive",
    "group-hover",
    "focus-within",
    "first",
    "last",
    "odd",
    "even",
    "hover",
    "focus",
    "active",
    "visited",
    "disabled",
  ],
  plugins: [
    require("@tailwindcss/forms"),
    plugin(function ({ addComponents, theme }) {
      const screens = theme("screens", {});
      addComponents([
        {
          ".container": { width: "100%" },
        },
        {
          [`@media (min-width: ${screens.sm})`]: {
            ".container": {
              "max-width": "640px",
            },
          },
        },
        {
          [`@media (min-width: ${screens.md})`]: {
            ".container": {
              "max-width": "768px",
            },
          },
        },
        {
          [`@media (min-width: ${screens.lg})`]: {
            ".container": {
              "max-width": "1024px",
            },
          },
        },
        {
          [`@media (min-width: ${screens.xl})`]: {
            ".container": {
              "max-width": "1280px",
            },
          },
        },
        {
          [`@media (min-width: ${screens["2xl"]})`]: {
            ".container": {
              "max-width": "1280px",
            },
          },
        },
      ]);
    }),
  ],
};
