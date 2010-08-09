<?php

require_once 'lib/data/om/BaseProject.php';
require_once 'lib/data/Acknowledgement.php';
require_once 'lib/data/curation/NCCuratedObjects.php';


/**
 * Skeleton subclass for representing a row from the 'Project' table.
 *
 *
 *
 * This class was autogenerated by Propel on:
 *
 * Sat Feb  9 00:03:06 2008
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.data
 *
 * @todo remove HTML from here, put it in a helper.
 *
 */
abstract class Project extends BaseProject {

//  /**
//   * Initializes internal state of Project object.
//   */
  function __construct($title = "",
                       $description = "",
                       $contactName = "",
                       $contactEmail = "",
                       $sysadminName = "",
                       $sysadminEmail = "",
                       $startDate = null,
                       $endDate = null,
                       $ack = "",            //String type, but insert to database as Array type
                       $view = "PUBLIC",
                       $projectTypeId = 1,
                       $NEES = TRUE,
                       $nickname = "",
                       $fundorg = "",
                       $fundorgprojid = "",
                       $name = "",
                       $creatorId = null,
                       $deleted = false,
                       $status = "",
                       $curationstatus = "Uncurated")
  {
    $this->setTitle($title);
    $this->setDescription($description);
    $this->setContactName($contactName);
    $this->setContactEmail($contactEmail);
    $this->setSysadminName($sysadminName);
    $this->setSysadminEmail($sysadminEmail);
    $this->setStartDate($startDate);
    $this->setEndDate($endDate);
    $this->setView($view);
    $this->setNEES($NEES);
    $this->setProjectTypeId($projectTypeId);
    $this->setNickname($nickname);
    $this->setFundorg($fundorg);
    $this->setFundorgProjID($fundorgprojid);
    $this->setName($name);
    $this->setCreatorId($creatorId);
    $this->setDeleted($deleted);
    $this->setStatus($status);
    $this->setCurationStatus($curationstatus);
    $this->setName((is_null($name)) ? "Temp" : $name); // Oracle doesn't accept empty name

    
//     echo "Project::title=$title, desc=$description, cn=$contactName, ce=$contactEmail,
//           sn=$sysadminName, se=$sysadminEmail, start=$startDate, end=$endDate,
//           ack=$ack, view=$view, type=$projectTypeId, nees=$NEES, nickname=$nickname,
//           fundorg=$fundorg, fundid=$fundorgprojid, name=$name, creator=$creatorId,
//           deleted=$deleted, status=$status, curate=$curationstatus<br><br>";
     
  }


  /**
   * Return the Web-Services URL that this instance is accessible at
   *
   * @return String RESTURI
   */
  function getRESTURI() {
    return "/Project/{$this->getId()}";
  }


############################# Project - Organization ################################
  /*
   * Get the list of Organization associated with this Project
   *
   * @return array <Organization>
   */
  public function getOrganizations() {
    $orgs = array();

    foreach($this->getProjectOrganizationsJoinOrganization() as $po) {
      $orgs[] = $po->getOrganization();
    }
    return $orgs;
  }


  /**
   * Add an Organization associated with this Project
   *
   * @param Organization $org
   */
  public function addOrganization( Organization $org ) {
    $org->addProject($this);
  }


  /**
   * Add an Organization associated with this Project
   *
   * @param Organization $org
   */
  public function removeOrganization(Organization $org) {
    $org->removeProject($this);
  }


  /**
   * Check if this Project associated with an Organization
   *
   * @param Organization $org
   * @return boolean value
   */
  public function hasOrganization(Organization $org) {
    return $org->hasProject($this);
  }


############################# Project - Experiment ################################

  /**
   * Set a list of Experiments to a Project
   *
   * @param $experiments: array <Experiment>
   */
  public function setExperiments($experiments) {
    if(is_null($experiments)) $experiments = array();

    return $this->collExperiments = $experiments;
  }


  /**
   * Add an Experiment to a Project
   *
   * @param Experiment $exp
   */
  function addExperiment( Experiment $exp ) {
    if(is_null($exp)) return;

    $this->collExperiments[] = $exp;
    $exp->setProject($this);
  }


  /**
   * Get the Experiment belong to this Project given by the ExperimentID or Experiment Name
   *
   * @param String $expname | int $expid
   * @return Experiment
   */
  function getExperiment($expname_or_id) {
    return ExperimentPeer::findOneInProject($this->getId(), $expname_or_id);
  }


  /**
   * Get the list of Viewable Experiment belong to this Project for the current user
   *
   * @return array[Experiment]
   */
  function getVisibleExperimentsWithInProject() {

    // If this Project is published, return all the experiments no matter what the Experiment is published or not
    if($this->isPublished()) {
      //return $this->getExperiments();          // Oops ! This return all the experiments marked with deleted = true, too
      return ExperimentPeer::findByProject($this->getId());
    }


    $auth = Authorizer::getInstance();
    if( $auth->getUserId()) {
      return ExperimentPeer::findMyExperimentsWithInProject($this->getId(), $auth->getUserId());
    }

    return null;
  }

############################# Project - Acknowledgement ################################


  /**
   * Get the Acknowledgement String associated with this Project.
   *
   * The Project class keeps a Collection of Acknowledgements, but the old
   * code only accommodated a single acknowledgement. Also, the GUI doesn't
   * seem to support anything other than a single string. So, going to
   * continue to replace instead of adding to a collection.
   *
   * @return String Acknowledgement (Sponsor column in database)
   */
  public function getProjectAcknowledgement() {
    $ack = AcknowledgementPeer::findByProjectOnly($this->getId());
    return is_null($ack) ? "" : $ack->getSponsor();
  }


  /**
   * Set the Project Acknownledgement
   *
   * @param String $ack Acknowledgement
   */
  public function setProjectAcknowledgement($ack) {

    $ack = substr(htmlspecialchars(trim($ack)), 0, 4000);

    $acknowledgement = AcknowledgementPeer::findByProjectOnly($this->getId());

    if(! empty($ack)) {
      if(is_null($acknowledgement)) {
        $acknowledgement = new Acknowledgement($this, null, null, $ack, null);
      }
      else {
        $acknowledgement->setSponsor($ack);
      }

      $acknowledgement->save();
    }
    // Empty ack
    elseif(!is_null($acknowledgement)) {
      $acknowledgement->delete();
    }
  }


############################# Project - Others ################################
  /**
   * Check to see if this Project is visible to the current login user
   *
   * @return booleab value
   */
  function isVisibleToCurrentUser() {
    if($this->getDeleted()) return false;
    if ($this->isPublished()) return true;
    if(Authenticator::getInstance()->isLoggedIn() && (Authorizer::getInstance()->canView($this))) return true;
    return false;
  }


  /**
   * Check if this Project is published or not
   *
   * @return boolean value
   */
  public function isPublished(){
    if ($this->getView() == "PUBLIC") {
      return true;
    }
    return false;
  }


  /**
   * Check if this Project is curated or not
   *
   * @return boolean value
   */
  public function isCurated(){
    if ($this->getCurationStatus() == "Curated") {
      return true;
    }
    return NCCuratedObjectsPeer::isCompleteCuratedProject($this->getName());
  }


  /**
   * Check if this Project is in the curation status or not
   *
   * @return boolean value
   */
  public function isInCuration(){
    if (($this->getCurationStatus() == "Curated") || ($this->getCurationStatus()=="Submitted")) {
      return true;
    }
    return false;
  }


  /**
   * Check if this project is type of Structured Project or not
   *
   * @return boolean value
   */
  public function isHybridProject() {
    return $this->getProjectTypeId() == ProjectPeer::CLASSKEY_HYBRIDPROJECT;
  }


  /**
   * Check if this project is type of Structured Project or not
   *
   * @return boolean value
   */
  public function isStructuredProject() {
    return $this->getProjectTypeId() == ProjectPeer::CLASSKEY_STRUCTUREDPROJECT;
  }


  /**
   * Check if this project is type of Unstructured Project or not
   *
   * @return boolean value
   */
  public function isUnstructuredProject() {
    return $this->getProjectTypeId() == ProjectPeer::CLASSKEY_UNSTRUCTUREDPROJECT;
  }


  /**
   * Check if this project is type of Supper Project or not
   *
   * @return boolean value
   */
  public function isProjectGroup() {
    return $this->getProjectTypeId() == ProjectPeer::CLASSKEY_SUPERPROJECT;
  }


  /**
   * Check if this project is type of Supper Project or not
   *
   * @return boolean value
   */
  public function isExperimentalProject() {
    return $this->isStructuredProject() || $this->isHybridProject();
  }


  /**
   * Check if this project is type of Supper Project or not
   *
   * @return boolean value
   */
  public function isSuperProject() {
    return $this->isProjectGroup();
  }


  /**
   * Check to see if this Project has at least one experiment is published
   *
   * @return int count
   */
  public function hasPublishedExperiments(){
    return self::getNumPublishedExperiments() > 0;
  }


  /**
   * Count the number of Experiment belong to this Project that are published
   *
   * @return int count
   */

  public function getNumPublishedExperiments() {
    return ProjectPeer::getNumPublicExperiments($this->getId());
  }


  /**
   * get the Class EntityType Name
   *
   * @return String 'Project'
   */
  public function getEntityTypeName() {
    return "Project";
  }


  /**
   * Each project is associated with a directory on disk. This function returns the path of that directory for this Project.
   *
   * @return String $path
   */
  public function getPathname() {
    return "/nees/home/" . $this->getName() . ".groups";
  }


  /**
   * Overwrite parent::getCurationStatus() because the column Curation_Status currently not updated
   *  @return String CurationStatus
   */
  public function getCurationStatus() {
    if(BaseProject::getCurationStatus() != "Uncurated") {
      return BaseProject::getCurationStatus();
    }

    $curatedObject = NCCuratedObjectsPeer::findByProjectName($this->getName());
    if(is_null($curatedObject)) {
      return "Uncurated";
    }
    return $curatedObject->getCurationState();
  }

  /**
   * Get the HTML code for display Project footer on NEEScentral UI
   *
   * @return String $html
   */
  public function getProjectInfoFooter() {

    $authorizer = Authorizer::getInstance();

    $curation = $this->getCurationStatus();

    $contact = "";
    if( $this->getView() == "PUBLIC" || $authorizer->hasRole()) {
      $contact = "<span style='white-space:nowrap;'>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>Contact:</strong> ";
      if ( strpos( $this->getContactEmail(), '@' ) ) {
        $contact .= "<a href='mailto&#58;" . $this->getContactEmail() . "'>" . $this->getContactName() . "</a>";
      }
      else {
        $contact .= $this->getContactName();
      }
      $contact .= "</span>";
    }

    $itcontact = "";

    if ( $this->getSysadminName() && $this->getSysadminEmail() ) {
      $itcontact = "<span style='white-space: nowrap;'>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>IT Contact:</strong> ";
      $itcontact .= "<a href='mailto&#58;" . $this->getSysadminEmail() . "'>" . $this->getSysadminName() . "</a>";
      $itcontact .="</span>";
    }

    //$startdate = cleanDate($this->getStartDate());
    //$enddate = cleanDate($this->getEndDate());

    $info = <<<ENDHTML

      <span style="white-space: nowrap;"><strong>Project ID:</strong> {$this->getId()} </span>
      <span style="white-space: nowrap;">&nbsp;&nbsp;|&nbsp;&nbsp;<strong>Name:</strong> {$this->getName()} </span>
      <span style="white-space: nowrap;">&nbsp;&nbsp;|&nbsp;&nbsp;<strong>Curation Status:</strong> $curation </span>
      $contact
      $itcontact
      <br/>

ENDHTML;

    return $info;
  }


  /**
   * Get the String image file name for display Project Icon on NEEScentral UI
   *
   * @return String $html
   */
  function getProjectIcon() {
    if($this->isPublished()) {
      return "icon_project_published_80x80.gif";
    }
    elseif($this->getView() == "MEMBERS") {
      return "icon_project_member_80x80.gif";
    }
    elseif($this->getView() == "USERS") {
      return "icon_project_member_80x80.gif";
    }
    else {
      return "icon_project_80x80.gif";
    }
  }


  /**
   * Return the Project Query, which helpful to create the link address for this Project
   * @return String
   */
  function getProjectQuery(){
    return "projid=" . $this->getId();
  }

  /**
   * Each project can be set with an thumbnail. If the thumbnail is not set, then return the default thumbnail
   *
   * @return DataFile: a thumbnail datafile of this project
   */
  function getProjectThumbnailDataFile() {
    return DataFilePeer::findThumbnailDataFile($this->getId(), 1);
  }


  function getProjectThumbnailHTML($p_strLinkImage) {
    $default_thumbnail = "";

    $projectImage = $this->getProjectThumbnailDataFile();

    $thumbnail = null;
    if($projectImage && file_exists($projectImage->getFullPath())) {
      $projectThumbnailId = $projectImage->getProjectImageThumbnailId();

      if($projectThumbnailId && $projectThumbnail = DataFilePeer::find($projectThumbnailId)) {
        if(file_exists($projectThumbnail->getFullPath())) {
          //$thumbnail = "<div class='thumb_frame'><a style='border-bottom:0px;' target='_blank' href='" . $projectImage->get_url() . "'><img src='" . $projectThumbnail->get_url() . "' title=\"Project Image. Click to view this project image with full size\"  alt=''/></a></div>";

          $strImageName = $projectImage->getName();

          // display the 800x600 when user clicks on it
          $strDisplayName = "display_".$projectImage->getId()."_".$strImageName;
          $projectImage->setName($strDisplayName);
          $projectImage->setPath($projectThumbnail->getPath());

          // display the 90x60 (approx) image
          $strIconName = $p_strLinkImage."_".$projectImage->getId()."_".$strImageName;
          $projectThumbnail->setName($strIconName);

          $thumbnail = "<div id='projThumb' align='center'><a style='border-bottom:0px;' target='_blank' href='" . $projectImage->get_url() . "' rel='lightbox'><img src='" . $projectThumbnail->get_url() . "'  alt='' style='float: left; margin-left: 30px;'></a></div>";
        }
      }
    }

    if(!$thumbnail) $thumbnail = $default_thumbnail;

    return $thumbnail;
  }


  function getProjectThumbnailListing() {
    $projectImage = $this->getProjectThumbnailDataFile();

    $thumbnail = null;
    if($projectImage && file_exists($projectImage->getFullPath())) {
      $projectThumbnailId = $projectImage->getImageThumbnailId();

      if($projectThumbnailId && $projectThumbnail = DataFilePeer::find($projectThumbnailId)) {
        if(file_exists($projectThumbnail->getFullPath())) {
          $thumbnail = "<div style='width:60px; height:60px;'><a onClick='move_to_project=false;' onmouseout='move_to_project=true;' target='_blank' href='" . $projectImage->get_url() . "'><img src='" . $projectThumbnail->get_url() . "' alt='' /></a></div>";
        }
      }
    }
    return $thumbnail;
  }

  /**
   * Get SuperProject related by SuperProjectId
   * @return Project
   */
  public function getSuperProject() {
    return $this->getProjectRelatedBySuperProjectId();
  }

  public function setSuperProject(SuperProject $superProject) {
    $this->setProjectRelatedBySuperProjectId($superProject);
  }



  /**
   * Return friendly toString function to display Project information
   *
   * @return String
   */
  public function toString() {
    return "Project ID: "     . $this->getId() .
           ", Project Name: " . $this->getName() .
           ", Title: "        . $this->getTitle() .
           ", Contact Email: ". $this->getContactEmail() .
           ", Contact Name: " . $this->getContactName() .
           ", Start Date: "   . $this->getStartDate() .
           ", End Date: "     . $this->getEndDate() .
           ", Viewable: "     . $this->getView();
  }
} // Project
?>
