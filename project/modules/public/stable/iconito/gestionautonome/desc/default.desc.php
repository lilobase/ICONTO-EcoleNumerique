<?php

// Général
$showTree = & new CopixAction ('default', 'processShowTree');
$displayPersonsData = & new CopixAction ('default', 'processDisplayPersonsData');
$updateTreeActions = & new CopixAction ('default', 'processUpdateTreeActions');

// Villes
$createCity = & new CopixAction ('default', 'processCreateCity');
$validateCityCreation = & new CopixAction ('default', 'processValidateCityCreation');

$updateCity = & new CopixAction ('default', 'processUpdateCity');
$validateCityUpdate = & new CopixAction ('default', 'processValidateCityUpdate');

$deleteCity = & new CopixAction ('default', 'processDeleteCity');

// Ecoles
$createSchool = & new CopixAction ('default', 'processCreateSchool');
$validateSchoolCreation = & new CopixAction ('default', 'processValidateSchoolCreation');

$updateSchool = & new CopixAction ('default', 'processUpdateSchool');
$validateSchoolUpdate = & new CopixAction ('default', 'processValidateSchoolUpdate');

$deleteSchool = & new CopixAction ('default', 'processDeleteSchool');

// Classes
$createClass = & new CopixAction ('default', 'processCreateClass');
$validateClassCreation = & new CopixAction ('default', 'processValidateClassCreation');

$updateClass = & new CopixAction ('default', 'processUpdateClass');
$validateClassUpdate = & new CopixAction ('default', 'processValidateClassUpdate');

$deleteClass = & new CopixAction ('default', 'processDeleteClass');

// Personnel
$createPersonnel = & new CopixAction ('default', 'processCreatePersonnel');
$validatePersonnelCreation = & new CopixAction ('default', 'processValidatePersonnelCreation');

$updatePersonnel = & new CopixAction ('default', 'processUpdatePersonnel');
$validatePersonnelUpdate = & new CopixAction ('default', 'processValidatePersonnelUpdate');

$deletePersonnel = & new CopixAction ('default', 'processDeletePersonnel');

// Eleves
$createStudent = & new CopixAction ('default', 'processCreateStudent');
$validateStudentCreation = & new CopixAction ('default', 'processValidateStudentCreation');

$updateStudent = & new CopixAction ('default', 'processUpdateStudent');
$validateStudentUpdate = & new CopixAction ('default', 'processValidateStudentUpdate');

$removeStudent = & new CopixAction ('default', 'processRemoveStudent');
$deleteStudent = & new CopixAction ('default', 'processDeleteStudent');

// Responsables
$createPersonInCharge = & new CopixAction ('default', 'processCreatePersonInCharge');
$validatePersonInChargeCreation = & new CopixAction ('default', 'processValidatePersonInChargeCreation');

$removePersonInCharge = & new CopixAction ('default', 'processRemovePersonInCharge');