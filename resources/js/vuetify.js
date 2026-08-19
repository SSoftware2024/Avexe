//VUETIFY
import "vuetify/styles";
import "@mdi/font/css/materialdesignicons.css";
import { createVuetify } from "vuetify";
import { VMaskInput } from 'vuetify/labs/VMaskInput' //máscaras 
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components: {
        ...components,
        VMaskInput,
    },
    directives,
    theme: { //para deixar o sistema escolher, basta comentar o default theme
        defaultTheme: "light",
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: "#212E40",
                    secondary: "#F26E22",
                    accent: "#F29877",
                    info: "#F29877",
                    warning: "#F25922",
                    background: "#FFFFFF",
                    surface: "#FFFFFF",
                    "surface-variant": "#F2F2F2",
                    "on-surface": "#212E40",
                    "on-primary": "#FFFFFF",
                    "on-secondary": "#FFFFFF",
                    "on-surface-variant": "#6B7280",
                },
            },
        },
    },
});
//FIM VUETIFY

export default vuetify;
