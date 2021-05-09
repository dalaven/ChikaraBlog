var input = document.getElementsByClassName("number")[0];
var button = document.getElementsByClassName("start")[0];
var board = document.getElementsByClassName("board")[0];

var parejas = 5;
var optionsCurrent;
var options

window.optionA = null;
window.optionB = null;
window.elementA = null;
window.elementB = null;


var init = async function () {
    let result = await fetchJSON("./data.json")
    this.options = result
    reset(this.parejas);
}

async function fetchJSON(url) {
    try {
        const response = await fetch(url);
        return await response.json()
    }
    catch (err) {
        console.log('fetch failed', err);
    }
}

button.addEventListener("click", function () {
    reset(input.valueAsNumber)
});


function showMore(e) {
    var element = e.target
    if (window.elementA === null || window.elementB === null) {
        if (element.classList[0] === "text" && element.classList[1] === "hidden") {

            element.classList.remove("hidden")
            element.classList.add("open")
            if (window.optionA === null) {
                window.optionA = element.dataset.info
                window.elementA = element
            } else {
                window.optionB = element.dataset.info
                window.elementB = element
                validate(window.optionA, window.optionB);
            }
        }
    } else {

    }

}

document.querySelector('body').addEventListener('click', showMore)

var validate = function (optionA, optionB) {
    let test = { A: optionA, B: optionB }
    let result = this.options.filter(c => (c.A === test.A && c.B === test.B) || (c.A === test.B && c.B === test.A))

    if (result.length === 1) {
        window.elementA.classList.add("correct")
        window.elementB.classList.add("correct")
        window.elementA.classList.remove("open")
        window.elementB.classList.remove("open")
        window.elementA = null
        window.elementB = null
        window.optionA = null
        window.optionB = null
    } else {
        setTimeout(() => {
            window.elementA.classList.add("hidden")
            window.elementB.classList.add("hidden")
            window.elementA.classList.remove("open")
            window.elementB.classList.remove("open")
            var other = document.getElementsByClassName("open")

            for (i = 0; i < other.length; other++) {
                other[i].classList.remove("open")
                other[i].classList.add("hidden")
            }


            window.elementA = null
            window.elementB = null
            window.optionA = null
            window.optionB = null
        }, 500);

    }

}



var reset = function (pare) {
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

}

init();
