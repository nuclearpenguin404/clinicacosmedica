# clinicacosmedica

Database + web interface for weight loss center in Mexico. The project was created in 2013.

PHP5, JS, jQuery, jqGrid, jqPlot

[**List of patients**](https://www.dropbox.com/s/qoblxbuiwxogm2a/PatientsList.png?dl=0)
The page with the list of patients is implemented using jqGrid-PHP. Patients data can be added, edited, deleted, exported to PDF and XLS. Live search is implemented. When you double click on the patient's record, you're redirected on patient's personal page with personal data, treatments and progress data.

[**Patient's personal page**]:(https://www.dropbox.com/s/vqy1gxr2mwpqps4/PatientPers.png?dl=0)
Patients personal and treatments data can be added, edited, deleted, exported to PDF and XLS. Live search is implemented. 

[**Patient's progress**](https://www.dropbox.com/s/onqo2c5gsu6nso7/Progress1.png?dl=0):
Progress data can be added, edited, deleted, exported to PDF and XLS. The graphs are redrawn after each change in the progress data. They are exported to pdf (not to xls), but they're not stored in the database.

[**List of therapists**](https://www.dropbox.com/s/d7hw9uxi57qgtjs/Doctors.png?dl=0):
The page with the list of doctors is implemented using jqGrid-PHP. Patients data can be added, edited, deleted, exported to PDF and XLS. Live search is implemented. The state "active" means that the doctor is available for the job, state "inactive" means that the doctor is not available, e.g. he/she is on holiday or retired. The control "active/inactive" allows to keep the records of the doctors that not work any-more in the database.

[**Therapist's patients**](https://www.dropbox.com/s/lb56d8zrl84ml70/DoctorsPatients.png?dl=0):
When you double click on the certain therapist record in the list of therapist, you're redirected to the list of patients that were treated by that therapist. When you double click on the patient's record, you're redirected on patient's personal page with personal data, treatments and progress data
