const subMenu = document.getElementById('sub-menu');
const profileMenu = document.getElementById("profile-menu");
document.getElementById("menu").onclick = function (){
    profileMenu.style.display = 'none';
    const subMenuStyle = getComputedStyle(subMenu).display;
    if(subMenuStyle === 'none'){
        subMenu.style.display = 'flex';
    }
    else{
        subMenu.style.display = 'none';
    }

}
document.getElementById("profile").onclick = function (){
    const subMenuStyle = getComputedStyle(subMenu).display;
    console.log(subMenuStyle)
    subMenu.style.display = 'none';
    const profileMenuStyle = getComputedStyle(profileMenu).display;
    if(profileMenuStyle === 'none'){
        profileMenu.style.display = 'flex';
    }
    else{
        profileMenu.style.display = 'none';
    }
}
