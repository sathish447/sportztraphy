import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MymatchComponent } from './mymatch.component';

describe('MymatchComponent', () => {
  let component: MymatchComponent;
  let fixture: ComponentFixture<MymatchComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MymatchComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MymatchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
