<?php

namespace StudentStipend\Service;

use StudentStipend\Mapper\StudentStipendMapperInterface;
use StudentStipend\Model\StudentStipend;

class StudentStipendService implements StudentStipendServiceInterface {

    protected $mapper;

    public function __construct(StudentStipendMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getUserDetailsId($tableName, $username){
        return $this->mapper->getUserDetailsId($tableName, $username);
    }

    public function getOrganisationId($tableName, $username){
        return $this->mapper->getOrganisationId($tableName, $username);
    }

    public function getUserDetails($username, $tableName){
        return $this->mapper->getUserDetails($username, $tableName);
    }

    public function getUserImage($username, $usertype){
        return $this->mapper->getUserImage($username, $usertype);
    }

    public function getStudentLists($stdName, $stdId, $organisation_id){
        return $this->mapper->getStudentLists($stdName, $stdId, $organisation_id);
    }

    public function getStudentListsToAdmin($stdName, $stdId,$organisation_id){
        return $this->mapper->getStudentListsToAdmin($stdName, $stdId,$organisation_id);
    }

    public function getStdPersonalDetails($id){
        return $this->mapper->getStdPersonalDetails($id);
    }

    public function listStudentStipendList($student_id, $id){
        return $this->mapper->listStudentStipendList($student_id, $id);
    }

    public function fetchFeeCategories($organisation_id){
        return $this->mapper->fetchFeeCategories($organisation_id);
    }

    public function isStudentStipendsPaid($data){
        return $this->mapper->isStudentStipendsPaid($data);
    }

    public function saveStudentStipendDetails(StudentStipend $studentStipendDetails){
        return $this->mapper->saveStudentStipendDetails($studentStipendDetails);
    }

    public function deleteStudentStipend($id){
        return $this->mapper->deleteStudentStipend($id);
    }

    public function generateBulkStudentStipend($data){
        return $this->mapper->generateBulkStudentStipend($data);
    }

    public function getStudentStipendListByFilter($data){
        return $this->mapper->getStudentStipendListByFilter($data);
    }
}
