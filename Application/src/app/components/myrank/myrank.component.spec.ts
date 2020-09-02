import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyrankComponent } from './myrank.component';

describe('MyrankComponent', () => {
  let component: MyrankComponent;
  let fixture: ComponentFixture<MyrankComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyrankComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyrankComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
