let btn = document.querySelector("#dcp_show_division")
let box = document.querySelector("#dcp-division")
if( btn )
{
    btn.addEventListener( 'click', () => {
        if( box )
        {
            box.classList.toggle('active')
        }
    } )
}
