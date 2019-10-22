import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {MzToastService} from '@ngx-materialize';
import {Select2OptionData} from 'ng2-select2';

declare var M: any;

@Component({
    selector: 'renova-file-upload',
    templateUrl: './file-upload.component.html',
    styleUrls: ['./file-upload.component.scss']
})
export class FileUploadComponent implements OnInit {

    selectedFile: File = null;
    commentFile: string;
    data: any; // modelo

    public exampleData: Array<Select2OptionData>;
    public options: Select2Options;

    constructor(private api: ApiService,
                private toast: MzToastService) {
    }

    ngOnInit() {
        M.updateTextFields();
        this.data = {
            name: '',
            comment: '',
            quantity: '',
            values: ['multiple2', 'multiple4']
        };

        this.exampleData = [
            {
                id: 'multiple1',
                text: 'Multiple 1'
            },
            {
                id: 'multiple2',
                text: 'Multiple 2'
            },
            {
                id: 'multiple3',
                text: 'Multiple 3'
            },
            {
                id: 'multiple4',
                text: 'Multiple 4'
            }
        ];

        this.options = {
            multiple: true
        };
    }

    handleFile(event) {
        console.log(<File>event.target.files[0].name);
        this.selectedFile = <File>event.target.files[0];
    }

    sendForm() {
        const fd = new FormData();
        fd.append('file', this.selectedFile, this.selectedFile.name);

        for (const index in this.data) {
            if (true) {
                fd.append(index, this.data[index]);
            }
        }

        this.api.post('couchdb/upload', fd)
            .subscribe(
            res => {
                this.toast.show(
                    res.message,
                    5000,
                    'green'
                );
            }
        );
    }

    changed(event) {
        console.log(event.value);
        this.data.values = event.value;
        console.log(this.data);
    }

}
