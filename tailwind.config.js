const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

function withOpacityValue(variable) {
    return ({ opacityValue }) => {
        if (opacityValue === undefined) {
            return `rgb(var(${variable}))`
        }
        return `rgb(var(${variable}) / ${opacityValue})`
    }
}

module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        './vendor/filament/**/*.blade.php',
        "./src/**/*.{html,js}",
        "./node_modules/tw-elements/dist/js/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    '50':  withOpacityValue('--color-primary-50'),
                    '100': withOpacityValue('--color-primary-100'),
                    '200': withOpacityValue('--color-primary-200'),
                    '300': withOpacityValue('--color-primary-300'),
                    '400': withOpacityValue('--color-primary-400'),
                    '500': withOpacityValue('--color-primary-500'),
                    '600': withOpacityValue('--color-primary-600'),
                    '700': withOpacityValue('--color-primary-700'),
                    '800': withOpacityValue('--color-primary-800'),
                    '900': withOpacityValue('--color-primary-900')
                },
                danger: colors.red,
                success: colors.green,
                warning: colors.amber,
                'purple-dark': '#43425D',
                'purple-normal': '#A3A0FB',
                'purple-light': '#F0F0F7',
                'dark': '#121212',
                'dark-2': '#1E1E1E',
            },
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('flowbite/plugin'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require("tw-elements/dist/plugin.cjs")
    ],
}
