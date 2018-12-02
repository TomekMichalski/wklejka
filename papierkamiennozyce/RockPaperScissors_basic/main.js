const paper = document.querySelector(".paper");
const rock = document.querySelector(".rock");
const scissors = document.querySelector(".scissors");
const playerScoreHtml = document.querySelector(".score--user");
const computerScoreHtml = document.querySelector(".score--computer");

const resultHtml = document.querySelector("h2");

const handler = ['p', 'k', 'n'];
var playerScore = 0;
var computerScore = 0;

paper.addEventListener('click', ()=> {
    let computerChoice = randomizeComputerChoice();
    let result = compareChoices('p', computerChoice);
    displayResults(result);
})

rock.addEventListener('click', ()=> {
    let computerChoice = randomizeComputerChoice();
    let result = compareChoices('k', computerChoice);
    displayResults(result);
})

scissors.addEventListener('click', ()=> {
    let computerChoice = randomizeComputerChoice();
    let result = compareChoices('n', computerChoice);
    displayResults(result);
})

var randomizeComputerChoice = () => {
    let computerChoice = Math.floor(Math.random() * 3);
    return handler[computerChoice];
}
var compareChoices = (playerChoice, computerChoice) => {
    let result = playerChoice + computerChoice;
    switch(result) {
        case 'pk':
        case 'kn':
        case 'np':
        return ['win', playerChoice, computerChoice];
        case 'kp':
        case 'nk':
        case 'pn':
        return ['lose', playerChoice, computerChoice];
        case 'pp':
        case 'kk':
        case 'nn':
        return ['draw', playerChoice, computerChoice];
    }
}
var displayResults = (result) => {
    if(result[0] == 'win') {
        playerScore++;
        playerScoreHtml.innerHTML = playerScore;
        resultHtml.innerHTML = `<span class="win-msg">Wygrałeś!</span>`
    } else if (result[0] == 'lose') {
        computerScore++;
        computerScoreHtml.innerHTML = computerScore;
        resultHtml.innerHTML = `<span class="lose-msg">Przegrałeś!</span>`
    } else {
        resultHtml.innerHTML = `Mamy <span class="win-msg">remis</span>!`
    }
}