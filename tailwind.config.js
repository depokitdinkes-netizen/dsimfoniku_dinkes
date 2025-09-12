/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {},
    },
    plugins: [require("daisyui")],
    daisyui: {
        themes: [
            {
                light: {
                    ...require("daisyui/src/theming/themes")["light"],
                    primary: "#3b90ff",
                    "primary-content": "#ffffff",
                    "error-content": "#ffffff",
                    "neutral-content": "#ffffff",
                    "success-content": "#ffffff",
                },
            },
        ],
    },
};
