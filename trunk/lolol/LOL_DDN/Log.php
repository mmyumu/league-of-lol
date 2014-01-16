<?php

	class Log {
		/**
		 * Classe Log
		 */

		/**
		 * Function stringifyArray
		 *
		 * @param	$p_aData	array	Array to be stringified
		 * @param	$p_iLvl		int		Level of stringification
		 */
		function stringifyArray ($p_aData, $p_iLvl = 0) {
			$sData = '';
			$bFirst = true;
			if (is_array($p_aData)) {
				$sData .= str_repeat('	', $p_iLvl) . ' => Array (';
				foreach($p_aData as $k => $v) {
					if ($bFirst) {
						$sData = "\n";
						$bFirst = false;
					}
					else {
						$sData .= ",\n";
					}
					if (is_array($v)) {
						$sData .= str_repeat('	', $p_iLvl) . $k . ' => Array ('
							   . __stringifyArray($v, $p_iLvl + 1) . "\n"
							   . str_repeat('	', $p_iLvl) . ')';
					}
					else {
						$sData .= str_repeat('	', $p_iLvl) . $k . ' => ' . $v;
					}
				}
				$sData .= str_repeat('	', $p_iLvl) . ')';
			}
			else {
				$sData .= $p_aData;
			}
			return $sData;
		}


		function record ($p_sMess) {
			// Definition of a default filename
			$sDefaultFile = 'errors.log';
			$sFileTime = @date('Ymd');
			$sPath = $sFileTime . '_' . $sDefaultFile;
			$rFile = @fopen($sPath, 'a');
			if ($rFile !== FALSE) {
				$sTime = @date(DATE_ISO8601);
				if (isset($_SERVER['REMOTE_ADDR'])) {
					$sTime .= ' - from ' . $_SERVER['REMOTE_ADDR'];
					if (isset($_SERVER['REMOTE_PORT'])) {
						$sTime .= ':' . $_SERVER['REMOTE_PORT'];
					}
				}
				$sTime .= ' - ' . session_id();
				if (fwrite($rFile, $sTime . ' : ' . $p_sMess . "\n") !== FALSE) {
					if (fclose($rFile) === FALSE) {
						error_log('Impossible to close the log file ' . $sPath . '', 0);
					}
				}
				else {
					error_log('Impossible to write in the log file ' . $sPath . '!', 0);
				}
			}
			else {
				error_log('Impossible to modify the log file ' . $sPath . '', 0);
			}
		}

		public function debug ($p_sMess) {
			$this->record('DEBUG: ' . $p_sMess);
		}

		public function error ($p_sMess) {
			$this->record('ERROR: ' . $p_sMess);
		}

		public function warning ($p_sMess) {
			$this->record('WARNING: ' . $p_sMess);
		}

		public function info ($p_sMess) {
			$this->record('INFO: ' . $p_sMess);
		}

		public function varDump ($p_var) {
			$this->record('VARIABLE: ' . $this->stringifyArray($p_var));
		}

		public function purge() {
			// Definition of a default filename
			$sDefaultFile = 'errors.log';
			$sFileTime = @date('Ymd');
			$sPath = $sFileTime . '_' . $sDefaultFile;
			unlink($sPath);
		}

		public function toScreen() {
			// Definition of a default filename
			$sDefaultFile = 'errors.log';
			$sFileTime = @date('Ymd');
			$sPath = $sFileTime . '_' . $sDefaultFile;
			$rFile = @fopen($sPath, 'r');
			if ($rFile !== FALSE) {
				$sContent = '<pre>';
				while (!feof($rFile)) {
				  $sContent .= fread($rFile, 8192);
				}
				$sContent .= '</pre>';
				fclose($rFile);
				print $sContent;
			}
			else {
				error_log('Impossible to open the log file ' . $sPath . '', 0);
			}
		}


	}
?>