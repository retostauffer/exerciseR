% Generated by roxygen2: do not edit by hand
% Please edit documentation in R/exr_compile.R
\name{exr_compile}
\alias{exr_compile}
\title{Compiles exerciseR exercises}
\usage{
exr_compile(file, overwrite = FALSE, verbose = FALSE)
}
\arguments{
\item{file}{\code{character}, name of the Rd file to be compiled.}

\item{overwrite}{\code{logical}, default \code{FALSE}. Prevents the user
from rendering the same Rmd file again if the \code{xml} file already
exists! You may not change the description/solution/tests if the exercise
has already been rolled out to the users!}

\item{verbose}{\code{logical}.}
}
\description{
Compile/parse an \code{.Rmd} file which contains an exerciseR
exercise.
}
\details{
Prepares an exerciseR exercise for the UI/UX.
The first input argument is the Rmd (R markdown) file which contains
the exercise. An exercise at least consists of a 'Settings' section,
the 'Description' and 'Solution' plus 'Tests'.

The Rmd file will be rendered using \code{rmarkdown::render} (Rmd to html)
and allows to include the standard R markdown elements. The html file will
be parsed to extract the required information, which will then be stored
in separate \code{xml} and \code{html} files as used by the exerciseR UI.

\itemize{
     \item Description: the description of the exercise as shown to the users.
     \item Solution: solution, will be provided depending on the settings in the UX.
     \item Settings: contains e.g., command blacklist/whitelist, points, files included
           in the exercise, title, and short description. May be extended in the future.
     \item Tests: a set of tests, can be visible or invisible for the user. Used to
           test user submissions.
}
}
\author{
Reto Stauffer
}
