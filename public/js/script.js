const avatar = document.getElementById('avatar')

if (avatar) {
    avatar.addEventListener('click', (e) => {
        e.preventDefault();
        const sidebar_info = document.getElementById('sidebar_info');
        sidebar_info.style.display = 'block'
    })
}

document.addEventListener('click', (e) => {
    const sidebarInfo = document.getElementById('sidebar_info');
    if (sidebarInfo && !sidebarInfo.contains(e.target) && e.target !== avatar) {
        sidebarInfo.style.display = 'none';
    }
});
