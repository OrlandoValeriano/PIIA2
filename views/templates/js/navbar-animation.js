const headerBlur = () => {
    const navHeader = document.getElementById("nav-bar");
    this.scrollY > 50 ? navHeader.classList.add("blur-header")
                       : navHeader.classList.remove("blur-header");  
}

window.addEventListener("scroll", headerBlur);
