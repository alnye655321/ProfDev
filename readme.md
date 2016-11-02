# Professional Development Tracker

## Description
An application built for higher education professional development tracking. Academic departments partially base their standings on accumulating hours of different kinds of professional development. The application automatically tracks and raises the standings of those participating. It also includes an approval system for the professional development to travel through different levels of the department.

## Usage Notes

### Roles:
Divided between administrative users, chairs, deans, VPs, and registrar. Each have slightly different views and a different role in the approval chain. Registrar is the final approval to trigger a rise in standing.

### Views:
- Activity - Lists every individual professional development activity
- Levels - Gives an overview of the current standing of each faculty member
- Pending - The approval area for new professional development submissions. View depends on role in the system
- Advance - Final approval area. View is for VPs and registrar

## Dependencies:
- [Datatables](https://github.com/DataTables/DataTables)
- [Boostrap 3.0](http://getbootstrap.com/)
- [jQuery 1.9.1](https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js)
- [jQuery UI 1.11.4](//code.jquery.com/ui/1.11.4/jquery-ui.js)
