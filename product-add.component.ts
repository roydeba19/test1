import { Component, OnInit, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import {FormGroup} from '@angular/forms';
import {ProductService} from '../product.service';

@Component({
  selector: 'app-product-add',
  templateUrl: './product-add.component.html',
  styleUrls: ['./product-add.component.css'],
  providers:[ProductService]
})
export class ProductAddComponent implements OnInit {
  productForm = new FormGroup({
    pid: new FormControl(''),
    name: new FormControl(''),
    price: new FormControl(''),
    image: new FormControl('')
  });

  productImageFile:File;
  @ViewChild('ProductImage') Product_Image;
  constructor(private ps:ProductService) { }

  ngOnInit() {
  }

  onSubmit() {
    // TODO: Use EventEmitter with form value
    //console.warn(this.productForm.value);
    const Image = this.Product_Image.nativeElement;

    if (Image.files && Image.files[0]) {
      this.productImageFile = Image.files[0];
    }

    const ImageFile:File = this.productImageFile;
    console.log(ImageFile);

    const formData:FormData = new FormData();
    formData.append('pid',this.productForm.value.pid);
    formData.append('name',this.productForm.value.name);
    formData.append('price',this.productForm.value.price);
    formData.append('image',ImageFile,ImageFile.name);

    // this.ps.addProduct(this.productForm.value).subscribe(res => {
    //   console.log(res);
	  // });
  }

}
