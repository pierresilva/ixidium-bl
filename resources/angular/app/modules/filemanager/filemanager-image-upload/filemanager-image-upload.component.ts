import {Component, OnInit} from '@angular/core';

@Component({
    selector: 'renova-filemanager-image-upload',
    templateUrl: './filemanager-image-upload.component.html',
    styleUrls: ['./filemanager-image-upload.component.scss']
})
export class FilemanagerImageUploadComponent implements OnInit {

    public datepickerOptionsOne: any = {
        clear: 'Clear', // Clear button text
        close: 'Ok',    // Ok button text
        today: 'Today', // Today button text
        closeOnClear: true,
        closeOnSelect: false,
        format: 'dddd, dd mmm, yyyy', // Visible date format (defaulted to formatSubmit if provided otherwise 'd mmmm, yyyy')
        formatSubmit: 'yyyy-mm-dd',   // Return value format (used to set/get value)
        onClose: () => {},//alert('Close has been invoked.'),
        onOpen: () => {},//alert('Open has been invoked.'),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 10,    // Creates a dropdown of 10 years to control year,
        // min: new Date(2018,7,20),
        // max: new Date(2018,7,26),
    };

    public datepickerOptionsTwo: any = {
        clear: 'Clear', // Clear button text
        close: 'Ok',    // Ok button text
        today: 'Today', // Today button text
        closeOnClear: true,
        closeOnSelect: false,
        format: 'dddd, dd mmm, yyyy', // Visible date format (defaulted to formatSubmit if provided otherwise 'd mmmm, yyyy')
        formatSubmit: 'yyyy-mm-dd',   // Return value format (used to set/get value)
        onClose: () => alert('Close has been invoked.'),
        onOpen: () => alert('Open has been invoked.'),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 10,    // Creates a dropdown of 10 years to control year
    };

    dateOfBirth = '2018-07-12'

    constructor() {
    }

    ngOnInit() {
    }

    changeOption(event) {
        this.datepickerOptionsTwo.min = new Date();
        this.datepickerOptionsTwo.max = new Date(2018, 12, 25);

        console.log(this.datepickerOptionsTwo);
    }

    popup(url, title, width, height) {
        let left = (screen.width / 2) - (width / 2);
        let top = (screen.height / 2) - (height / 2);
        let options = '';
        options += ',width=' + width;
        options += ',height=' + height;
        options += ',top=' + top;
        options += ',left=' + left;
        return window.open(url, title, options);
    }

    setData(data) {
        console.log(data);
        let strData = JSON.stringify(data);
        document.getElementById('retrievedData').innerHTML = strData;
        let requestBinUrl = 'http://requestb.in/18u87g81';
        // window.location.href = requestBinUrl + '?data=' + strData;
    }

}
