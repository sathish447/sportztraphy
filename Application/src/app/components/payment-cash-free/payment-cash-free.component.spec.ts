import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PaymentCashFreeComponent } from './payment-cash-free.component';

describe('PaymentCashFreeComponent', () => {
  let component: PaymentCashFreeComponent;
  let fixture: ComponentFixture<PaymentCashFreeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PaymentCashFreeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PaymentCashFreeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
