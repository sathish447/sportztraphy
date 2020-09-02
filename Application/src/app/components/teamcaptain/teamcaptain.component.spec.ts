import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TeamcaptainComponent } from './teamcaptain.component';

describe('TeamcaptainComponent', () => {
  let component: TeamcaptainComponent;
  let fixture: ComponentFixture<TeamcaptainComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TeamcaptainComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TeamcaptainComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
