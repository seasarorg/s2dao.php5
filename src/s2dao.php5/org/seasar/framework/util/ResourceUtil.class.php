<?php

/**
 * @author nowel
 */
final class ResourceUtil {

	private function ResourceUtil() {
	}

	public static function getResourcePath($path, $extension) {
        if( is_object($path) ){
            return str_replace(".", "/", $path->getName()) . ".class.php";
        }
		if ($extension == null) {
			return path;
		}
		$extension = "." . $extension;
		if (ereg("{$extension}$", $path)) {
			return path;
		}
		return str_replace(".", "/", $path) . $extension;
	}
	
	public static function getResource($path, $extension = null) {
        if( $extension == null ){
		    return self::getResource($path, "");
        } else {
		    $url = $this->getResourceNoException($path, $extension);
		    if ($url != null) {
			    return $url;
		    } else {
			    throw new ResourceNotFoundRuntimeException($this->getResourcePath($path, $extension));
		    }
        }
	}
	
	public static function getResourceNoException($path, $extension = null) {
        if( $extension == null ){
		    return $this->getResourceNoException(path, "");
        } else {
		    $path = $this->getResourcePath($path, $extension);
            require_once ($path);
	    }
    }
	
	public static function isExist($path) {
		return $this->getResourceNoException($path) != null;
	}
	
    /*
	public static function getProperties($path) {
		$props = new Properties();
		InputStream is = getResourceAsStream(path);
		try {
			props.load(is);
			return props;
		} catch (IOException ex) {
			throw new IORuntimeException(ex);
		}
	}
	
	public static String getExtension(String path) {
		int extPos = path.lastIndexOf(".");
		if (extPos >= 0) {
			return path.substring(extPos + 1);
		} else {
			return null;
		}
	}
	
	public static String removeExtension(String path) {
		int extPos = path.lastIndexOf(".");
		if (extPos >= 0) {
			return path.substring(0, extPos);
		} else {
			return path;
		}
	}

	public static File getBuildDir(Class clazz) {
		URL url = getResource(getResourcePath(clazz));
		int num = StringUtil.split(clazz.getName(), ".").length;
		File file = new File(getFileName(url));
		for (int i = 0; i < num; ++i, file = file.getParentFile()) {
		}
		return file;
	}
	
	public static String toExternalForm(URL url) {
		String s = url.toExternalForm();
		try {
			return URLDecoder.decode(s, "UTF8");
		} catch (UnsupportedEncodingException ex) {
			ex.printStackTrace();
			throw new RuntimeException(ex);
		}
	}
	
	public static String getFileName(URL url) {
		String s = url.getFile();
		try {
			return URLDecoder.decode(s, "UTF8");
		} catch (UnsupportedEncodingException ex) {
			ex.printStackTrace();
			throw new RuntimeException(ex);
		}
	}
	
	public static File getFile(URL url) {
		return new File(getFileName(url));
	}
	
	public static File getResourceAsFile(String path) {
		return getResourceAsFile(path, null);
	}
	
	public static File getResourceAsFile(String path, String extension) {
		return getFile(getResource(path, extension));
	}
    */
}
?>
