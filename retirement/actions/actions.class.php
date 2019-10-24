<?php

/**
 * Retirement actions.
 *
 * @package    orangehrm
 * @subpackage Retirement
 * @author     JBL
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
//require_once '../../lib/common/LocaleUtil.php';
include ('../../lib/common/LocaleUtil.php');

class RetirementActions extends sfActions {

    public function executeRetirement(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();
            $retirementDao = new retirementDao();

            $this->sorter = new ListSorter('Benifit', 'wbm', $this->getUser(), array('b.from_date', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('retirement/retirement');
                }
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.from_date' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $retirementDao->searchRetiremen($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->retrmentlist = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeRetirementserviceextension(sfWebRequest $request) {

        $this->myCulture = $this->getUser()->getCulture();
        $retirementDao = new retirementDao();
        $retirement = new RetirementExtension();

        if ($request->getParameter('id') != null) {

            $this->symplodret = $retirementDao->readRetirement($request->getParameter('id'));
            if (($request->getParameter('id') != null) && ($request->getParameter('rid') != null)) {
                //Table Lock code is Open


                if (!strlen($request->getParameter('lock'))) {
                    $this->lockMode = 0;
                } else {
                    $this->lockMode = $request->getParameter('lock');
                }
                $transEid = $request->getParameter('id');
                $transRid = $request->getParameter('rid');
                if (isset($this->lockMode)) {
                    if ($this->lockMode == 1) {

                        $conHandler = new ConcurrencyHandler();

                        $recordLocked = $conHandler->setTableLock('hs_hr_ret_retirement', array($transEid, $transRid), 1);

                        if ($recordLocked) {
                            // Display page in edit mode
                            $this->lockMode = 1;
                        } else {
                            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                            $this->lockMode = 0;
                        }
                    } else if ($this->lockMode == 0) {
                        $conHandler = new ConcurrencyHandler();
                        $recordLocked = $conHandler->resetTableLock('hs_hr_ret_retirement', array($transEid, $transRid), 1);
                        $this->lockMode = 0;
                    }
                }

                //Table lock code is closed

                $retirement = $retirementDao->readRetirementext($request->getParameter('id'), $request->getParameter('rid'));
                $this->retupdate = $retirement;

                $this->etid = $this->retupdate[0]['emp_number'];
                $ename = $retirementDao->getEmployeerecord($this->retupdate[0]['emp_number']);
                // var_dump($retirementDao->getEmployeerecord($this->retupdate[0]['emp_number']));
                $this->empfname = $ename[0]['firstName'] . " " . $ename[0]['lastName'];
                $this->retid = $this->retupdate[0]['ret_id'];
                $this->extfdate = $this->retupdate[0]['from_date'];
                $this->exttdate = $this->retupdate[0]['to_date'];
                $this->extclause = $this->retupdate[0]['clause'];
                $this->extcomment = $this->retupdate[0]['comment'];
            } else {
                $this->btn = "new";
                $this->etid = $this->symplodret[0]['emp_number'];
                $ename = $retirementDao->getEmployeerecord($this->symplodret[0]['emp_number']);
                $this->empfname = $ename[0]['firstName'] . " " . $ename[0]['lastName'];

                $this->maxretid = $retirementDao->readmaxretid($this->etid);
                $this->retid = $this->maxretid[0]['MAX'] + 1;
            }
        }

        if ($request->isMethod('post')) {
            try {
                $retirement = new RetirementExtension();
                $retirement->setEmp_number($request->getParameter('txtEmpId'));
                $retirement->setRet_id($request->getParameter('txtretid'));
                if($request->getParameter('txtfromdate')!= null){
                $retirement->setFrom_date(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtfromdate')));
                }else{
                 $retirement->setFrom_date(null);   
                }
                if($request->getParameter('txttodate')!=null){
                $retirement->setTo_date(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txttodate')));
                }else{
                    $retirement->setTo_date(null);
                }
                $retirement->setClause($request->getParameter('txtclause'));
                $retirement->setComment(trim($request->getParameter('txtcomment')));
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
            }
            $retirementc = $retirementDao->readRetirementext($request->getParameter('txtEmpId'), $request->getParameter('txtretid'));
            $this->fieldchexck = $retirementc;
            if (($this->fieldchexck = $retirementc) == NULL) {
                $abc = $request->getParameter('txtEmpId');
                try {
                    $retirementDao->saveRetirement($retirement);
                } catch (Doctrine_Connection_Exception $e) {
                    $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                    $this->setMessage('WARNING', $errMsg->display());
                } catch (Exception $e) {
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                }
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
                $this->redirect('retirement/retirementserviceextension/?id=' . $abc);
            } else {
                $abc = $request->getParameter('txtEmpId');
                try {
                    $retirementDao->updateRetirement($request->getParameter('txtEmpId'), $request->getParameter('txtretid'), $request->getParameter('txtfromdate'), $request->getParameter('txttodate'), $request->getParameter('txtclause'), $request->getParameter('txtcomment'));
                } catch (Doctrine_Connection_Exception $e) {
                    $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                    $this->setMessage('WARNING', $errMsg->display());
                } catch (Exception $e) {
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                }
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_ret_retirement', array($request->getParameter('txtEmpId'), $request->getParameter('txtretid')), 1);
                $this->lockMode = 0;
                $this->redirect('retirement/retirementserviceextension/?id=' . $request->getParameter('txtEmpId') . '?rid=' . $request->getParameter('txtretid') . '&lock=0');
            }
        }
    }

    public function executeAjaxCall(sfWebRequest $request) {
        $this->culture = $this->getUser()->getCulture();
        $abc = $request->getParameter('sendValue') or $request->getParameter($id);
        $empretrement = new retirementDao();
        $this->value1 = $empretrement->readRetirement($abc);

        echo json_encode($this->value1);
        die;
    }

    public function executeAjaxADateConvert(sfWebRequest $request) {

        $date = $request->getParameter('date');
        $this->value1 = LocaleUtil::getInstance()->formatDate($date);
        echo json_encode(array("date" => $this->value1));
        die;
    }

    public function setMessage($messageType, $message = array()) {
        $this->getUser()->setFlash('messageType', $messageType);
        $this->getUser()->setFlash('message', $message);
    }

    public function executeDeleteRetirement(sfWebRequest $request) {
        $retirementDao = new retirementDao();
        try {
            $conHandler = new ConcurrencyHandler();
            $isRecordLocked = $conHandler->isTableLocked('hs_hr_ret_retirement', array($request->getParameter('id'), $request->getParameter('rid')), 1);
            if ($isRecordLocked) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            } else {
                $retirementDao->deleteretirement($request->getParameter('id'), $request->getParameter('rid'));
                $conHandler->resetTableLock('hs_hr_ret_retirement', array($request->getParameter('id'), $request->getParameter('rid')), 1);
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            }
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
        }



        $abc = $request->getParameter('id');
        $this->redirect('retirement/retirementserviceextension/?id=' . $abc);
    }

    public function executeAjaxCalllast(sfWebRequest $request) {
        $this->culture = $this->getUser()->getCulture();
        $this->value = $request->getParameter('sendValue2');
        $empretrement = new retirementDao();
        $this->value1 = $empretrement->readmaxretid($this->value);
        echo json_encode($this->value1[0]['MAX']);
        die;
    }

    public function executeDeleteRet(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {
            $retirementDao = new retirementDao();
            try {
                var_dump($request->getParameter('chkLocID'));
                die;

                $retirementDao->deleteReason($request->getParameter('chkLocID'));
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
        } else {
            $this->setMessage('NOTICE', array($this->getContext()->getI18N()->__("Select at least one record to delete", $args, 'messages')));
        }
        $this->redirect('retirement/retirement');
    }

    public function executeError(sfWebRequest $request) {

        $this->redirect('default/error');
    }

}