const toggleButtonStyle = (input, button) => {
    if(input.checked){
        button.classList.replace(
            'permission-btn-enabled',
            'permission-btn-disabled'
        );
    } else {
        button.classList.replace(
            'permission-btn-disabled',
            'permission-btn-enabled'
        );
    }
}

const permissions = [
    'products',
    'clients',
    'providers',
    'sellers',
    'purchases',
    'sales',
    'kardex',
    'cash-closing',
    'inventory',
    'permissions'
];

for(let permission of permissions){
    const input = document.getElementById(`${permission}Input`),
          button = document.getElementById(`${permission}Btn`);

    button.addEventListener('click', (event) => {
        event.preventDefault();
        toggleButtonStyle(input, button);
        input.checked = !input.checked
    });
}
