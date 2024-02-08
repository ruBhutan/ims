<?php

namespace StudentStipend\Mapper;

use StudentStipend\Model\StudentStipend;

interface StudentStipendMapperInterface {
    public function getUserDetailsId($tableName, $username);

    public function getOrganisationId($tableName, $username);

    public function getUserDetails($username, $tableName);

    public function getUserImage($username, $usertype);

    public function getStudentLists($stdName, $stdId, $organisation_id);

    public function getStudentListsToAdmin($stdName, $stdId,$organisation_id);

    public function getStdPersonalDetails($id);

    public function listStudentStipendList($student_id, $id);

    public function fetchFeeCategories($organisation_id);

    public function isStudentStipendsPaid($data);

    public function saveStudentStipendDetails(StudentStipend $studentStipendDetails);

    public function deleteStudentStipend($id);

    public function generateBulkStudentStipend($data);

    public function getStudentStipendListByFilter($data);
}
