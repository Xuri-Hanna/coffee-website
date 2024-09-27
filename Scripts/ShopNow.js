
const buttons = document.querySelectorAll(".nav-button");
reset();
setButtonActive(buttons[0]);

buttons.forEach((button) => {

    button.addEventListener("click",() => {
        setButtonActive(button);
    });
})
function setButtonActive(button){
    reset();
    button.classList.add('button-active');
}
function reset(){
    buttons.forEach((button) => {
        button.classList.remove("button-active");
    })
}