<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Department;
use AppBundle\Entity\User;
use AppBundle\Entity\Semester;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use \DateTime;
use AppBundle\Entity\Forum;
use AppBundle\Form\Type\CreateForumType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ControlPanelController extends Controller {

    public function showAction(){
		$em = $this->getDoctrine()->getManager();

		$department = $this->get('security.token_storage')->getToken()->getUser()->getFieldOfStudy()->getDepartment();
		$semester = null;

		try{
			$semester = $em->getRepository('AppBundle:Semester')->findCurrentSemesterByDepartment($department->getId());
		}catch(Exception $e){
			if ($e instanceof \Doctrine\ORM\NoResultException OR $e instanceof \Doctrine\ORM\NonUniqueResultException) {
				$semester = null;
			}
			else{
				throw $e;
			}
		}

		$step = 0;
		$interviewedAssistantsCount = 0;
		$assignedInterviewsCount = 0;
		$allocatedAssistantsCount = 0;
		$totalAssistantsCount = 0;

		if(!is_null($semester)){
			$applicationRepository = $this->getDoctrine()->getRepository('AppBundle:Application');
			$interviewedAssistantsCount = count($applicationRepository->findInterviewedApplicants($department,$semester));
			$assignedInterviewsCount = count($applicationRepository->findAssignedApplicants($department,$semester));

			$totalAssistantsCount = 0;
			$allocatedAssistantsCount = 0;
			$assistantHistoryRepository = $this->getDoctrine()->getRepository('AppBundle:AssistantHistory');
			$assistantHistories = $assistantHistoryRepository->findAssistantHistoriesByDepartment($department);
			foreach($assistantHistories as $history){
				$now = new \DateTime();
				if($history->getSemester()->getSemesterStartDate()<$now && $history->getSemester()->getSemesterEndDate() > $now){
					$totalAssistantsCount += 1;
					if(!is_null($history->getSchool())){
						$allocatedAssistantsCount += 1;
					}
				}
			}
			$step = $this->determineCurrentStep($semester, $interviewedAssistantsCount, $assignedInterviewsCount, $allocatedAssistantsCount, $totalAssistantsCount);
		}

		$departments = $this->getDoctrine()->getRepository('AppBundle:Department')->findAll();

		// Return the view to be rendered
		return $this->render('control_panel/index.html.twig', array(
			'step' => $step,
			'semester' => $semester,
			'interviewedAssistantsCount' => $interviewedAssistantsCount,
			'totalInterviewsCount' => $assignedInterviewsCount + $interviewedAssistantsCount,
			'allocatedAssistantsCount' => $allocatedAssistantsCount,
			'totalAssistantsCount' => $totalAssistantsCount,
			'departments' => $departments,
		));

	}

	public function determineCurrentStep(Semester $semester, $interviewedAssistantsCount, $assignedInterviewsCount, $allocatedAssistantsCount, $totalAssistantsCount){

		$today = new DateTime("now");
		//$today = DateTime::createFromFormat('j-m-Y', '03-06-2016'); //For testing

		// Step 1 Before Admission
		if($today < $semester->getAdmissionStartDate()&& $today > $semester->getSemesterStartDate()){
			return 1 + ($today->format('U')-$semester->getSemesterStartDate()->format('U'))/($semester->getAdmissionStartDate()->format('U') - $semester->getSemesterStartDate()->format('U'));
		}

		// Step 2 Admission has started
		if($today < $semester->getAdmissionEndDate() && $today > $semester->getAdmissionStartDate()){
			return 2 + ($today->format('U') - $semester->getAdmissionStartDate()->format('U'))/($semester->getAdmissionEndDate()->format('U') - $semester->getAdmissionStartDate()->format('U'));
		}

		// Step 3 Interviewing
		// No interviews are assigned yet
		if($assignedInterviewsCount == 0 && $interviewedAssistantsCount == 0){
			return 3;
		} // There are interviews left to conduct
		elseif($assignedInterviewsCount>0){
			return 3 + $interviewedAssistantsCount/($assignedInterviewsCount + $interviewedAssistantsCount);
		}

		// Step 4 Distribute to schools
		// All interviews are conducted, but no one has been accepted yet
		if($totalAssistantsCount == 0){
			return 4;
		}
		// There are assistants left to distribute
		if($allocatedAssistantsCount < $totalAssistantsCount){
			return 4 + $allocatedAssistantsCount/$totalAssistantsCount;
		}

		// Step 5 Operating phase
		if($today < $semester->getSemesterEndDate() && $today > $semester->getAdmissionEndDate()){
			return 5 + ($today->format('U') - $semester->getAdmissionEndDate()->format('U'))/($semester->getSemesterEndDate()->format('U') - $semester->getAdmissionEndDate()->format('U'));
		}

		// Something is wrong
		return -1;
	}

}
