import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MycontestComponent } from './mycontest.component';

describe('MycontestComponent', () => {
  let component: MycontestComponent;
  let fixture: ComponentFixture<MycontestComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MycontestComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MycontestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
