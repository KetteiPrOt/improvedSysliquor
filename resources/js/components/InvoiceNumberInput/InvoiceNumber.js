class InvoiceNumber {
    constructor(){
        this.inputs = [
            document.getElementById('invoiceNumberFirstInput'),
            document.getElementById('invoiceNumberSecondInput'),
            document.getElementById('invoiceNumberThirdInput')
        ];
    }

    validate(input, maxsize, event){        
        const allowedKeys = [
            'Backspace',
            'Tab',
            'ArrowLeft',
            'ArrowRight'
        ];
        let validKey = false;
        for(let allowedKey of allowedKeys){
            if(event.key == allowedKey){
                validKey = true;
                break;
            }
        }
        if(
            (input.value.length == maxsize) && (!validKey)
        ){
            event.preventDefault();
        }
    }
}

export {InvoiceNumber};