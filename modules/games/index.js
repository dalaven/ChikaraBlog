define([
    "text!./index.html",
    './components/memory'
], (html, memory) => {
    return {
        template: html,
        data() {
            return {
                game: false
            };
        },
        computed: {
            gameActive() {
                return this.game ? this.game : null;
            }
        },
        methods: {

        },
        created() {

        },
        components: {
            memory
        }
    };
});