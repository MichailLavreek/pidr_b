import {Component, OnInit} from '@angular/core';
import {Router} from "@angular/router";
import {FormControl, FormGroup} from "@angular/forms";
import moment = require("moment");
import {CustomersService} from "../customers.service";

@Component({
    selector: 'app-add-customer',
    templateUrl: './add-customer.component.html',
    styleUrls: ['./add-customer.component.scss']
})
export class AddCustomerComponent implements OnInit {

    public form: FormGroup = new FormGroup({
        date: new FormControl(),
        address: new FormControl(),
        description: new FormControl(),
        phones: new FormControl(),
        name: new FormControl(),
        processed: new FormControl(),
        processedDescription: new FormControl(),
    });

    constructor(private router: Router, private customersService: CustomersService) {
    }

    ngOnInit() {
    }

    public submit() {
        (<any>Object).values(this.form.controls).forEach(control => {
            control.markAsTouched();
        });

        if (this.form.status !== 'VALID') {
            return;
        }

        let customer = JSON.parse(JSON.stringify(this.form.value));
        customer.date = Math.floor(this.form.value.date.getTime() / 1000);
        console.log(customer);

        this.customersService.setCustomer(customer).then((response) => {
            console.log(response);
            this.router.navigate(['/']);
        }).catch((error) => {
            console.log(error);
        });
    }

    public cancel() {
        this.router.navigate(['/']);
    }
}
