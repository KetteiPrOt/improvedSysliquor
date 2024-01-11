import { InvoiceNumber } from "./InvoiceNumber";

const invoiceNumber = new InvoiceNumber;

invoiceNumber.inputs[0].addEventListener('keydown', (event) => {
    let input = invoiceNumber.inputs[0];
    invoiceNumber.validate(input, 3, event);
});

invoiceNumber.inputs[1].addEventListener('keydown', (event) => {
    let input = invoiceNumber.inputs[1];
    invoiceNumber.validate(input, 3, event);
});

invoiceNumber.inputs[2].addEventListener('keydown', (event) => {
    let input = invoiceNumber.inputs[2];
    invoiceNumber.validate(input, 9, event);
});