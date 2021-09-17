define([
    "text!./memory.html"
], (html) => {
    return {
        template: html,
        data() {
            return {
                couples: 5,
                optionsCurrent:null,
                options:null,


                optionA: null,
                optionB: null,
                elementA: null,
                elementB: null,

                input:null,
                button:null,
                board:null
            };
        },
        computed: {

        },
        methods: {
            async fetchJSON(url) {
                try {
                    const response = await fetch(url);
                    return await response.json();
                }
                catch (err) {
                    console.log('fetch failed', err);
                }
            },
            reset(pare) {
                while (this.board.firstChild) {
                    this.board.removeChild(this.board.firstChild);
                }

                this.options.sort(function () { return Math.random() - 0.5 });

                start = Math.floor(Math.random() * (this.options.length - pare))

                this.optionsCurrent = this.options.slice(start, start + pare)

                var setOptions = []

                this.optionsCurrent.forEach(element => {
                    setOptions.push(element.A)
                    setOptions.push(element.B)
                });


                setOptions.sort(function () { return Math.random() - 0.5 });



                setOptions.forEach(element => {
                    var card = document.createElement('div')

                    card.setAttribute('class', 'card');


                    var text = document.createElement('div')
                    text.setAttribute('data-info', element);
                    text.setAttribute('class', 'text hidden');
                    text.innerHTML = element
                    card.appendChild(text)

                    this.board.appendChild(card)
                });

            },
            showMore(e) {
                var element = e.target
                if (this.elementA === null || this.elementB === null) {
                    if (element.classList[0] === "text" && element.classList[1] === "hidden") {

                        element.classList.remove("hidden")
                        element.classList.add("open")
                        if (this.optionA === null) {
                            this.optionA = element.dataset.info
                            this.elementA = element
                        } else {
                            this.optionB = element.dataset.info
                            this.elementB = element
                            this.validate(this.optionA, this.optionB);
                        }
                    }
                } else {

                }

            },
            validate(optionA, optionB) {
                let test = { A: optionA, B: optionB }
                let result = this.options.filter(c => (c.A === test.A && c.B === test.B) || (c.A === test.B && c.B === test.A))

                if (result.length === 1) {
                    this.elementA.classList.add("correct")
                    this.elementB.classList.add("correct")
                    this.elementA.classList.remove("open")
                    this.elementB.classList.remove("open")
                    this.elementA = null
                    this.elementB = null
                    this.optionA = null
                    this.optionB = null
                } else {
                    setTimeout(() => {
                        this.elementA.classList.add("hidden")
                        this.elementB.classList.add("hidden")
                        this.elementA.classList.remove("open")
                        this.elementB.classList.remove("open")
                        var other = document.getElementsByClassName("open")

                        for (i = 0; i < other.length; other++) {
                            other[i].classList.remove("open")
                            other[i].classList.add("hidden")
                        }


                        this.elementA = null
                        this.elementB = null
                        this.optionA = null
                        this.optionB = null
                    }, 500);

                }

            }

        },

        mounted() {
            this.input = document.getElementsByClassName("number")[0];
            this.button = document.getElementsByClassName("start")[0];
            this.board = document.getElementsByClassName("board")[0];

            this.button.addEventListener("click", function () {
                this.reset(this.input.valueAsNumber)
            });

            document.querySelector('body').addEventListener('click', this.showMore)
        },
        async created() {
            let result = await this.fetchJSON("modules/games/components/data.json")
            this.options = result
            this.reset(this.couples);
        },
    };
});




