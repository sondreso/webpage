<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use \DateTime;
use AppBundle\Entity\Forum;
use AppBundle\Form\Type\CreateForumType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ControlPanelController extends Controller {

    public function showAction(){	

		$departments = $this->getDoctrine()->getRepository('AppBundle:Department')->findAll();
		// Return the view to be rendered
		return $this->render('control_panel/index.html.twig', array(
			'departments' => $departments,
		));
			
	}

	public function determineCurrentStep($user){
		$em = $this->getDoctrine()->getManager();

		$department= $user->getFieldOfStudy()->getDepartment();
		$semesters = $em->getRepository('AppBundle:Semester')->findAllSemestersByDepartment($department->getId());

		$today = new DateTime("now");

		$validSemesters = array();

		foreach ($semesters as $semester) {

			$semesterStartDate = $semester->getAdmissionStartDate();
			$semesterEndDate = $semester->getAdmissionEndDate();

			if ($semesterStartDate < $today && $today < $semesterEndDate) {
				$validSemesters[] = $semester;
			}
		}

		//Ingen gyldige semestre eksisterer, vi er på steg 0
		if(empty($validSemesters)){
			return 0;
		}

		//Kan ikke ha mer en et gyldig semester om gangen..,
		if(count($validSemesters)>1){
			return -1;
		}

		$semester = $validSemesters[0];

		if($semester->getSemesterStartDate()<$today && $today > $semester->getAdmissionStartDate()){
			return 1 + ($today->format('U')-$semester->getSemesterStartDate()->format('U'))/($semester->getAdmissionStartDate()->format('U') - $semester->getSemesterStartDate()>format('U'));
		}

		if($semester->getAdmissionEndDate()<$today && $today > $semester->getAdmissionStartDate()){
			return 2 + ($today->format('U') - $semester->getAdmissionStartDate()->format('U'))/($semester->getAdmissionEndDate()->format('U') - $semester->getAdmissionStartDate()>format('U'));
		}

		return -1;
	}

}
