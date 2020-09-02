import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TeampreviewComponent } from './teampreview.component';

describe('TeampreviewComponent', () => {
  let component: TeampreviewComponent;
  let fixture: ComponentFixture<TeampreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TeampreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TeampreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
