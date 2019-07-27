import {Component, OnInit, ViewChild} from '@angular/core';
import {CustomersService} from "./customers.service";
import moment = require("moment");
import {MatTableDataSource} from '@angular/material/table';
import {MatSort} from '@angular/material/sort';
import {Router} from "@angular/router";
import {MatDialog, MatPaginator} from "@angular/material";
import {DialogContentComponent} from "./dialog-content/dialog-content.component";

@Component({
    selector: 'app-customers',
    templateUrl: './customers.component.html',
    styleUrls: ['./customers.component.scss']
})
export class CustomersComponent implements OnInit {
    public customers: any;
    displayedColumns: string[] = ['date', 'address', 'description', 'phones', 'name', 'processed', 'rating', 'processedDescription', 'deleteButton', 'editButton'];
    @ViewChild(MatSort) sort: MatSort;
    @ViewChild('scheduledOrdersPaginator') paginator: MatPaginator;

    constructor(private customersService: CustomersService, private router: Router, public dialog: MatDialog) {
        this.initData();
    }

    ngOnInit() {

    }

    private initData() {
        this.customersService.getCustomers().then((response: any) => {
            this.customers = new MatTableDataSource(response.customers.map(item => {
                item.date = moment(Date.parse(item.date.date.substr(0, 10))).format('DD.MM.YY');
                return item;
            }));

            setTimeout(() => {
                this.customers.sort = this.sort;
                this.customers.paginator = this.paginator
            });

        });
    }

    applyFilter(filterValue: string) {
        this.customers.filter = filterValue.trim().toLowerCase();
    }

    public deleteCustomer(customer) {
        const dialogRef = this.dialog.open(DialogContentComponent);

        dialogRef.afterClosed().subscribe(result => {
            if (result) {
                this.customersService.deleteCustomer(customer.id).then(() => {
                    this.initData();
                });
            }
        });
    }

    public editCustomer(customer) {
        this.router.navigate(['/edit-customer'], {queryParams: {id: customer.id}});
    }
}
