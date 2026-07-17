//VUETIFY
import "vuetify/styles";
import "@mdi/font/css/materialdesignicons.css";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components,
    directives,
    theme: { //para deixar o sistema escolher, basta comentar o default theme
        defaultTheme: "light",
    },
});
//FIM VUETIFY

export default vuetify;
