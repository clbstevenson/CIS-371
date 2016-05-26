//package edu.gvsu.cis371;
/**
 * Represents a URL
 * Caleb Stevenson
 */
public class MyURL {
    private String scheme = "http";
    private String domainName = null;
    private int port = 80;
    private String path = "/";

    /**
     * Split {@code url} into the various components of a URL
     *
     * @param url the {@code String} to parse
     */
    public MyURL(String url) {

        // TODO:  Split the url into scheme, domainName, port, and path.
        // Only the domainName is required.  Default values given above.
        // See the test file for examples of correct and incorrect behavior.
        // Hints:  (1) My implementation is mostly calls to String.indexOf and String.substring.
        // (2) indexOf can take a String as a parameter (it need not be a single character).

        splitURLWithScheme(url);

    }

    /**
     * If {@code newURL} has a scheme (e.g., begins with "http://", "ftp://", etc), then parse {@code newURL}
     * and ignore {@code currentURL}.  If {@code newURL} does not have a scheme, then assume it is intended
     * to be a relative link and replace the file component of {@code currentURL}'s path with {@code newURL}.
     *
     * @param newURL     a {@code String} representing the new URL.
     * @param currentURL the current URL
     */
    public MyURL(String newURL, MyURL currentURL) {

        // If newURL has a scheme, then take the same action as the other constructor.
        if(newURL.contains("://")) {
            splitURLWithScheme(newURL);
        } else {
            // If newURL does not have a scheme
            // (1) Make a copy of currentURL
            MyURL currentURLCopy = currentURL;
            // (2) Replace the filename (i.e., the last segment of the path) with the relative link.
            // See the test file for examples of correct and incorrect behavior.
            // Hint:  Consider using String.lastIndexOf

            scheme = currentURLCopy.scheme;
            domainName = currentURLCopy.domainName;
            port = currentURLCopy.port;
            String currentPath = currentURLCopy.path;
            int endOfPath = currentPath.lastIndexOf("/"); // find the end of path/file as the last "/"
            // if no file or path, then set path to "/newURL"
            if(endOfPath == -1)
                path = "/" + newURL;
            else {
                // Found the end of the path, before the filename.
                // Take the substring up to and including the "/" before the old filename.
                // If the last directory is the same as the directory 
                // in the new URL, then only change /file not /path/file
                System.out.println("currentPath contains a dir");
                String shortenedPath = currentPath.substring(0,endOfPath);
                int newURLEndOfPath = newURL.lastIndexOf("/");
                if(newURLEndOfPath == -1) {
                    // If newURL does not have a path, then just append
                    // the filename to currentPath
                    path = currentPath.substring(0, endOfPath + 1) 
                        + newURL;
                    System.out.println("newURL does NOT contain a dir");
                } else {
                    System.out.println("newURL DOES contain a dir");
                    String newURLPath = newURL.substring(0,newURLEndOfPath);
                    int newURLPathIndex = currentPath.indexOf(newURLPath);
                    if(newURLPathIndex == -1) {
                        System.out.println("currentPath does NOT contain newURL");
                        // If newURLPath is not in currentPath, 
                        // then just append newURL to currentPath
                        path = currentPath.substring(0, endOfPath + 1) 
                            + newURL;
                    } else {
                        System.out.println("currentPath DOES contain newURL");
                        // If currentPath contains newURLPath,
                        // then only append the file to currentPath
                        String newURLFile = newURL.substring(newURLPathIndex);
                        path = currentPath.substring(0, endOfPath + 1) 
                            + newURLFile; 
                    }
                }
                // Append the newURL to the end of the path.
                //path = currentPath.substring(0, endOfPath + 1) + newURL;
            }
        }

    }

    private void splitURLWithScheme(String url) {
        // Below is the layout of a URL, as described at w3schools.com/html/html_urlencode.asp.
        //   scheme://prefix.domain:port/path/filename
        // For MyURL, path and filename will be combined and considered as 'path'
        // and prefix and domain will be combined and considered as 'domainName'

        String originalUrl = url;
        // 1. Find the value of 'scheme' of the url
        // find the index of the first occurrence of "://", which marks the end of the scheme portion
        int endOfScheme = originalUrl.indexOf("://");
        // If endOfScheme is -1, then could not find "://". This means that the scheme is not specified,
        // so use DEFAULT_SCHEME of http.
        if(endOfScheme == -1) {
            // Since scheme is not specified, keep scheme as DEFAULT_SCHEME
            // scheme = scheme;
            // set endOfScheme to index 0, to be used later to find domain, port, and path
            endOfScheme = 0;
        } else {
            scheme = originalUrl.substring(0, endOfScheme); // get the scheme from url, excluding end index
        }

        // 2. Find the value of 'domainName' of the url
        // find the index of next occurrence of ":" AFTER the scheme's "://",
        // so begin searching at (endOfScheme + 3) so the "://" text is not included in the search.
        // If endOfScheme was -1, then start at index 0. Otherwise, offset with 3 for the characters in "://".
        String urlAfterScheme = (endOfScheme == 0) ? originalUrl.substring(endOfScheme) : originalUrl.substring(endOfScheme + 3);
        int endOfDomain = urlAfterScheme.indexOf(':'); // index of delimiter between domain and port
        int endOfPort;
        // If endOfDomain is -1, then there is not a ':', so port isn't specified.
        if(endOfDomain == -1) {
            // port = port;
            // For reference, without a port the url looks like:
            //   scheme://prefix.domain/path/filename
            // The index of endOfDomain will not be until the next '/' without a specified port
            endOfDomain = urlAfterScheme.indexOf('/');
            // Now, if endOfDomain is -1, then there is not a file specified.
            if(endOfDomain == -1) {
                // Set domainName to be the remainder of the url text.
                domainName = urlAfterScheme; //domainName = urlAfterScheme.substring(0);
                // Keep 'path' set as "/"
                // path = path;

            } else {
                domainName = urlAfterScheme.substring(0, endOfDomain);
                // With no port, then path will be the rest of the URL after domainName.
                path = urlAfterScheme.substring(endOfDomain); // include the found "/" character in the path.
            }

        } else { // otherwise, found a ':' so port is specified
            domainName = urlAfterScheme.substring(0, endOfDomain); // get the domainName, excluding end index
            // 3. Find the value of 'port' of the url
            // find index of next occurence of "/" AFTER the colon delimiter
            // search area is part of urlWithoutScheme following index of ":" plus 1
            String urlAfterDomain = urlAfterScheme.substring(endOfDomain + 1);
            endOfPort = urlAfterDomain.indexOf('/');

            // Now, if endOfPort is -1, then there is not a file specified.
            if(endOfPort == -1) {
                // Set port to be the remainder of the URL text (as an int).
                port = Integer.parseInt(urlAfterDomain); //port = Integer.parseInt(urlAfterDomain.substring(0));
                // Keep 'path' set as "/"
                // path = path;

            } else {
                // Otherwise, there is a path/filename specified
                port = Integer.parseInt(urlAfterDomain.substring(0, endOfPort)); // convert port string to Integer
                // 4. Find the value of 'path' of the url
                // since both path and filename are considered combined for MyUrl,
                // then the remaining text of the url will be the path.
                // The remaining text will equal all text following the endOfPort index, and will be "" if no file is specified.
                path = urlAfterDomain.substring(endOfPort); // include the '/' in the path string
            }
        }
        // If domainName is still null, then throw a RuntimeException
        if(domainName.isEmpty())
            throw new RuntimeException("Domain is null");
    }


    public String scheme() {
        return scheme;
    }

    public String domainName() {
        return domainName;
    }

    public int port() {
        return port;
    }

    public String path() {
        return path;
    }

    /**
     * Format this URL as a {@code String}
     *
     * @return this URL formatted as a string.
     */
    public String toString() {
        // TODO:  Format this URL as a string
        return String.format("%s://%s:%d%s", scheme, domainName, port, path);
    }

    // Needed in order to use MyURL as a key to a HashMap
    @Override
    public int hashCode() {
        return toString().hashCode();
    }

    // Needed in order to use MyURL as a key to a HashMap
    @Override
    public boolean equals(Object other) {
        if (other instanceof MyURL) {
            MyURL otherURL = (MyURL) other;
            return this.scheme.equals(otherURL.scheme) &&
                    this.domainName.equals(otherURL.domainName) &&
                    this.port == otherURL.port() &&
                    this.path.equals(otherURL.path);
        } else {
            return false;
        }
    }
} // end class
