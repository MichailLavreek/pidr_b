import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {FormControl, FormGroup} from "@angular/forms";
import moment = require("moment");
import {CustomersService} from "../customers.service";

@Component({
    selector: 'app-edit-customer',
    templateUrl: './edit-customer.component.html',
    styleUrls: ['./edit-customer.component.scss']
})
export class EditCustomerComponent implements OnInit {
    private customer;
    private customerId;

    public form: FormGroup = new FormGroup({
        id: new FormControl(),
        date: new FormControl(),
        address: new FormControl(),
        description: new FormControl(),
        phones: new FormControl(),
        name: new FormControl(),
        processed: new FormControl(),
        processedDescription: new FormControl(),
    });

    constructor(private router: Router, private customersService: CustomersService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.route.queryParams
            .subscribe(params => {
                this.customerId = params.id;
                this.customersService.getCustomer(this.customerId).then((customer) => {
                    this.customer = customer;
                    this.customer.date = new Date(this.customer.date.date);
                    console.log(this.customer.date);
                    this.form.setValue(customer);
                });
            });
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
            this.router.navigate(['/']);
        }).catch((error) => {
            console.log(error);
        });
    }

    public cancel() {
        this.router.navigate(['/']);
    }
}
