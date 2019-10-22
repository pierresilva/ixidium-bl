import {AfterViewInit, Component, ElementRef, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Utilities} from '../../clasess/utilities';

declare var $: any;

@Component({
    selector: 'renova-image-upload',
    templateUrl: './image-upload.component.html',
    styleUrls: ['./image-upload.component.scss']
})
export class ImageUploadComponent implements OnInit, AfterViewInit {

    @Output() onFileReaded: EventEmitter<any> = new EventEmitter<any>();

    height = 200;
    @Input() set heightIn(data) {
        this.height = data;
    }

    width = 200;
    @Input() set widthIn(data) {
        this.width = data;
    }

    private imageFile;
    private utils = new Utilities();
    uuid = this.utils.createUUID();
    constructor(private element: ElementRef) { }

    ngOnInit() {
        console.log(this.uuid);
    }

    ngAfterViewInit() {

        const $element = $(this.element.nativeElement);
        const input = $element.find('input');
        const $div = $element.find('div');

        input.on('change', (e) => {
            this.getImagesFromEvent(e);
            e.stopPropagation();
        }).on('click', (e) => {
            e.stopPropagation();
        });

        $div.on('drag dragstart dragend dragover dragenter dragleave drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', () => {
                $div.addClass('is-dragover');
            })
            .on('dragleave dragend drop', () => {
                $div.removeClass('is-dragover');
            })
            .on('drop', (e) => {
                $element.find('input[type=\'file\']').prop('files', e.originalEvent.target.files || e.originalEvent.dataTransfer.files);
                this.getImagesFromEvent(e);
            })
            .on('click', (e) => {
                e.stopPropagation();
                e.preventDefault();
                input.trigger('click');
            });
    }

    getImagesFromEvent(event) {
        const files = event.originalEvent.target.files || event.originalEvent.dataTransfer.files;
        let count = 1;
        let image = null;

        for (const file of files) {
            if (file.type.match('image.*') !== null && count === 1) {
                image = file;
                count += 1;
            } else if (count > 1) {
                console.warn('Solo se puede procesar una imágen a la vez!');
            }
        }
        this.displayImage(image);
    }

    private displayImage(imagen) {
        const lector = this.generateReader(imagen);
        lector.readAsDataURL(imagen);
    }

    private generateReader(img) {

        const reader = new FileReader();
        reader.onload = ((file) => {
            return (e) => {
                this.imageFile = {
                    source: e.target.result,
                    file: file,
                    name: file.name
                };
                this.onFileReaded.emit(this.imageFile);
                $('#' + this.uuid).css({'background-image': 'url(' + this.imageFile.source + ')'});
            };
        })(img);

        return reader;
    }

}
